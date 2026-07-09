<?php

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Product;
use App\Domain\Order\Models\Order;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {
    $base = rtrim((string) config('app.url', url('/')), '/');
    $sitemap = $base . '/sitemap.xml';

    $lines = [
        'User-agent: *',
        'Disallow:',
        'Allow: /',
        '',
        'Sitemap: ' . $sitemap,
        '',
    ];

    return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('robots');

Route::get('/sitemap.xml', function () {
    $base = rtrim((string) config('app.url', url('/')), '/');

    $urls = [];
    $addUrl = function (string $loc, ?\Carbon\CarbonInterface $lastmod = null, string $changefreq = 'weekly', string $priority = '0.7') use (&$urls) {
        $urls[] = [
            'loc' => $loc,
            'lastmod' => $lastmod?->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    };

    $addUrl($base . '/', null, 'weekly', '1.0');
    $addUrl($base . '/products', null, 'weekly', '0.9');
    $addUrl($base . '/contact', null, 'monthly', '0.6');

    // Products (most important for SEO)
    \App\Domain\Catalog\Models\Product::query()
        ->with('category')
        ->select(['id', 'slug', 'updated_at', 'category_id'])
        ->whereNotNull('slug')
        ->get()
        ->each(function (\App\Domain\Catalog\Models\Product $product) use ($addUrl) {
            $categorySlug = $product->category?->slug ?: 'uncategorized';
            $loc = route('products.show', [$categorySlug, $product->slug], false);
            $addUrl(url($loc), $product->updated_at, 'weekly', '0.8');
        });

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $u) {
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . e($u['loc']) . "</loc>\n";
        if (!empty($u['lastmod'])) {
            $xml .= '    <lastmod>' . e($u['lastmod']) . "</lastmod>\n";
        }
        $xml .= '    <changefreq>' . e($u['changefreq']) . "</changefreq>\n";
        $xml .= '    <priority>' . e($u['priority']) . "</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= "</urlset>\n";

    return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

Route::middleware(['locale'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', fn () => redirect()->route('home', [], 301)->withFragment('o-nama'))->name('about');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{categorySlug}/{productSlug}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'productsCount' => Product::count(),
        'categoriesCount' => Category::count(),
        'ordersCount' => Order::count(),
    ]);
})->middleware(['auth', 'verified', 'locale'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin', 'locale'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::get('product', [AdminProductController::class, 'create'])->name('product'); // /admin/product -> forma za novi proizvod
    Route::resource('products', AdminProductController::class)->except('show');
    Route::get('order', [AdminOrderController::class, 'index'])->name('order'); // /admin/order -> porudžbine
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}/csv', [AdminOrderController::class, 'downloadCsv'])->name('orders.downloadCsv');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

require __DIR__.'/auth.php';
