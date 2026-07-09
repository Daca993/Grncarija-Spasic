<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductSize;
use App\Domain\Catalog\Models\ProductTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const DEFAULT_CURRENCY = 'RSD';
    private function generateUniqueSlug(string $name, ?int $ignoreProductId = null): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            $base = 'proizvod';
        }

        $slug = Str::limit($base, 100, '');
        $i = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignoreProductId, fn ($q) => $q->where('id', '!=', $ignoreProductId))
                ->exists()
        ) {
            $suffix = '-' . $i;
            $slug = Str::limit($base, 100 - strlen($suffix), '') . $suffix;
            $i++;
        }

        return $slug;
    }

    public function index(Request $request): View
    {
        $query = Product::with(['category.translations', 'translations'])->orderBy('sort_order');
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $products = $query->get();
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }

    private const ADMIN_PRODUCT_LOCALE = 'sr';

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price_mala' => 'required|numeric|min:0',
            'price_srednja' => 'required|numeric|min:0',
            'price_velika' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->storeAs('products', Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');
        }

        $slug = $this->generateUniqueSlug((string) $request->input('name'));
        $product = Product::create([
            'category_id' => $request->category_id,
            'slug' => $slug,
            'image' => $path,
            'price' => $request->price_srednja,
            'currency' => self::DEFAULT_CURRENCY,
            'unit' => $request->input('unit', 'cm'),
            'size_mala_cm' => $request->input('size_mala_cm'),
            'size_srednja_cm' => $request->input('size_srednja_cm'),
            'size_velika_cm' => $request->input('size_velika_cm'),
            'sort_order' => (int) ($request->sort_order ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);
        ProductTranslation::create([
            'product_id' => $product->id,
            'locale' => self::ADMIN_PRODUCT_LOCALE,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        foreach (['mala' => ['price_mala', 'size_mala_cm'], 'srednja' => ['price_srednja', 'size_srednja_cm'], 'velika' => ['price_velika', 'size_velika_cm']] as $size => [$priceKey, $cmKey]) {
            ProductSize::create([
                'product_id' => $product->id,
                'size' => $size,
                'size_cm' => $request->input($cmKey),
                'price' => $request->input($priceKey),
            ]);
        }
        return redirect()->route('admin.products.index')->with('success', __('messages.admin_product_created'));
    }

    public function edit(Product $product): View
    {
        $product->load('translations', 'productSizes', 'category.translations');
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price_mala' => 'required|numeric|min:0',
            'price_srednja' => 'required|numeric|min:0',
            'price_velika' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
        $path = $product->image;
        if ($request->hasFile('image')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $file = $request->file('image');
            $path = $file->storeAs('products', Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');
        }

        $slug = $product->slug ?: $this->generateUniqueSlug((string) $request->input('name'), $product->id);
        $product->update([
            'category_id' => $request->category_id,
            'slug' => $slug,
            'image' => $path,
            'price' => $request->price_srednja,
            'currency' => self::DEFAULT_CURRENCY,
            'unit' => $request->input('unit', 'cm'),
            'size_mala_cm' => $request->input('size_mala_cm'),
            'size_srednja_cm' => $request->input('size_srednja_cm'),
            'size_velika_cm' => $request->input('size_velika_cm'),
            'sort_order' => (int) ($request->sort_order ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);
        $trans = $product->translations()->where('locale', self::ADMIN_PRODUCT_LOCALE)->first();
        if ($trans) {
            $trans->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);
        } else {
            ProductTranslation::create([
                'product_id' => $product->id,
                'locale' => self::ADMIN_PRODUCT_LOCALE,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);
        }
        foreach (['mala' => ['price_mala', 'size_mala_cm'], 'srednja' => ['price_srednja', 'size_srednja_cm'], 'velika' => ['price_velika', 'size_velika_cm']] as $size => [$priceKey, $cmKey]) {
            ProductSize::updateOrCreate(
                ['product_id' => $product->id, 'size' => $size],
                ['size_cm' => $request->input($cmKey), 'price' => $request->input($priceKey)]
            );
        }
        return redirect()->route('admin.products.index')->with('success', __('messages.admin_product_updated'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', __('messages.admin_product_deleted'));
    }
}
