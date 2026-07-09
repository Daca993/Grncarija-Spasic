<?php

namespace App\Domain\Order\Models;

use App\Domain\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'product_name', 'size', 'size_cm', 'unit', 'price', 'quantity', 'subtotal'];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /** Prikaz veličine sa vrednošću i jedinicom, npr. "Mala - 18 cm" ili "Srednja - 2 L (litri)". */
    public function getSizeLabelWithCmAttribute(): string
    {
        if (!$this->size) {
            return '—';
        }
        $label = Product::SIZES[$this->size] ?? $this->size;
        if (!$this->size_cm || trim($this->size_cm) === '') {
            return $label;
        }
        $value = trim($this->size_cm);
        $unit = $this->unit ?? 'cm';
        $unitLabel = Product::UNITS[$unit] ?? $unit;
        if ($unitLabel && stripos($value, $unit) === false && stripos($value, $unitLabel) === false) {
            $value = $value . ' ' . $unitLabel;
        }
        return $label . ' - ' . $value;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
