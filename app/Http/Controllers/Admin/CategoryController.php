<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\CategoryTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    private const ADMIN_CATEGORY_LOCALE = 'sr';

    public function index(): View
    {
        $categories = Category::with('translations')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'slug' => 'required|string|max:100|unique:categories,slug',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);
        // U bazi čuvamo putanju sa ekstenzijom (npr. categories/abc.png); fajl u storage/app/public/categories/
        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->storeAs('categories', Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');
        }
        $category = Category::create([
            'slug' => $request->slug,
            'image' => $path,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);
        CategoryTranslation::create([
            'category_id' => $category->id,
            'locale' => self::ADMIN_CATEGORY_LOCALE,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('admin.categories.index')->with('success', __('messages.admin_category_created'));
    }

    public function edit(Category $category): View
    {
        $category->load('translations');
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'slug' => 'required|string|max:100|unique:categories,slug,' . $category->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);
        $path = $category->image;
        if ($request->hasFile('image')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $file = $request->file('image');
            $path = $file->storeAs('categories', Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');
        }
        $category->update([
            'slug' => $request->slug,
            'image' => $path,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);
        $trans = $category->translations()->where('locale', self::ADMIN_CATEGORY_LOCALE)->first();
        if ($trans) {
            $trans->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);
        } else {
            CategoryTranslation::create([
                'category_id' => $category->id,
                'locale' => self::ADMIN_CATEGORY_LOCALE,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);
        }
        return redirect()->route('admin.categories.index')->with('success', __('messages.admin_category_updated'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', __('messages.admin_category_deleted'));
    }
}
