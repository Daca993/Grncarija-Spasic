<?php

namespace App\Domain\Cart\Services;

use App\Domain\Cart\Models\CartItem;
use App\Domain\Catalog\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getIdentifier(): ?string
    {
        if (Auth::check()) {
            return null;
        }
        return Session::getId();
    }

    public function getUserId(): ?int
    {
        return Auth::id();
    }

    public function items()
    {
        $query = CartItem::with('product.category', 'product.productSizes');
        if (Auth::check()) {
            $query->where('user_id', Auth::id())->whereNull('session_id');
        } else {
            $query->where('session_id', Session::getId())->whereNull('user_id');
        }
        return $query->get();
    }

    public function count(): int
    {
        $query = CartItem::query();
        if (Auth::check()) {
            $query->where('user_id', Auth::id())->whereNull('session_id');
        } else {
            $query->where('session_id', Session::getId())->whereNull('user_id');
        }
        return (int) $query->sum('quantity');
    }

    public function total(): float
    {
        $total = 0;
        foreach ($this->items() as $item) {
            $total += $item->subtotal;
        }
        return round($total, 2);
    }

    public function add(Product $product, int $quantity = 1, string $size = 'srednja'): CartItem
    {
        $userId = $this->getUserId();
        $sessionId = $this->getIdentifier();
        $size = in_array($size, ['mala', 'srednja', 'velika'], true) ? $size : 'srednja';

        $cartItem = CartItem::when($userId, fn ($q) => $q->where('user_id', $userId)->whereNull('session_id'))
            ->when($sessionId, fn ($q) => $q->where('session_id', $sessionId)->whereNull('user_id'))
            ->where('product_id', $product->id)
            ->where('size', $size)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
            return $cartItem->fresh(['product']);
        }

        return CartItem::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'size' => $size,
            'quantity' => $quantity,
        ])->load('product');
    }

    public function update(int $cartItemId, int $quantity): bool
    {
        $item = $this->findItem($cartItemId);
        if (!$item) {
            return false;
        }
        if ($quantity <= 0) {
            $item->delete();
            return true;
        }
        $item->update(['quantity' => $quantity]);
        return true;
    }

    public function remove(int $cartItemId): bool
    {
        $item = $this->findItem($cartItemId);
        if (!$item) {
            return false;
        }
        $item->delete();
        return true;
    }

    protected function findItem(int $id): ?CartItem
    {
        $query = CartItem::query();
        if (Auth::check()) {
            $query->where('user_id', Auth::id())->whereNull('session_id');
        } else {
            $query->where('session_id', Session::getId())->whereNull('user_id');
        }
        return $query->find($id);
    }

    public function clear(): void
    {
        $query = CartItem::query();
        if (Auth::check()) {
            $query->where('user_id', Auth::id())->whereNull('session_id');
        } else {
            $query->where('session_id', Session::getId())->whereNull('user_id');
        }
        $query->delete();
    }

    /** Merge session cart into user cart after login */
    public function mergeSessionCartIntoUser(int $userId): void
    {
        $sessionId = Session::getId();
        $items = CartItem::where('session_id', $sessionId)->whereNull('user_id')->get();
        foreach ($items as $item) {
            $existing = CartItem::where('user_id', $userId)->whereNull('session_id')
                ->where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->first();
            if ($existing) {
                $existing->increment('quantity', $item->quantity);
                $item->delete();
            } else {
                $item->update(['user_id' => $userId, 'session_id' => null]);
            }
        }
    }
}
