<?php

namespace App\Http\Controllers;

use App\Domain\Catalog\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('products.index');
    }

    public function show(string $categorySlug, string $productSlug): View
    {
        $product = Product::where('slug', $productSlug)
            ->where('is_active', true)
            ->whereHas('category', fn ($q) => $q->where('slug', $categorySlug))
            ->with(['category.translations', 'translations', 'productSizes'])
            ->firstOrFail();
        return view('products.show', compact('product'));
    }
}
