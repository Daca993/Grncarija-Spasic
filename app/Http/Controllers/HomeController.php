<?php

namespace App\Http\Controllers;

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['translations', 'products' => fn ($q) => $q->where('is_active', true)->limit(1)])
            ->limit(6)
            ->get();
        $featuredProducts = Product::where('is_active', true)
            ->with(['category.translations', 'translations'])
            ->orderBy('sort_order')
            ->limit(8)
            ->get();
        return view('home', compact('categories', 'featuredProducts'));
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
    }
}
