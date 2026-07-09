<?php

namespace App\Domain\Cart\Models;

use App\Domain\Catalog\Models\Product;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $table = 'cart';

    protected $fillable = ['session_id', 'user_id', 'product_id', 'size', 'quantity'];

    public function getSizeLabelAttribute(): string
    {
        $size = $this->size ?? 'srednja';
        return \App\Domain\Catalog\Models\Product::SIZES[$size] ?? $size;
    }

    public function getSizeCmDisplayAttribute(): ?string
    {
        return $this->product?->getSizeCm($this->size);
    }

    /** Jedan string za prikaz veličine sa cm, npr. "Mala - 18 cm". */
    public function getSizeLabelWithCmAttribute(): string
    {
        $size = $this->size ?? 'srednja';
        return $this->product?->getSizeLabelWithCm($size) ?? $this->size_label;
    }

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Cena po komadu za izabranu veličinu. */
    public function getPriceAttribute(): float
    {
        return $this->product->getPriceForSize($this->size ?? 'srednja');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->price * $this->quantity;
    }
}
