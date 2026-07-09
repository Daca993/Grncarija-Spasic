<?php

namespace App\Http\Controllers;

use App\Domain\Cart\Services\CartService;
use App\Domain\Catalog\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart
    ) {}

    public function index(): View
    {
        return view('cart.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99',
            'size' => 'nullable|in:mala,srednja,velika',
        ]);
        $product = Product::where('is_active', true)->findOrFail($request->product_id);
        $this->cart->add($product, (int) ($request->quantity ?? 1), $request->input('size', 'srednja'));
        return back()->with('success', __('messages.Add to cart'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:99']);
        $this->cart->update($id, (int) $request->quantity);
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->cart->remove($id);
        return back();
    }
}
