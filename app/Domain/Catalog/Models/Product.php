<?php

namespace App\Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'slug', 'image', 'price', 'currency', 'unit',
        'size_mala_cm', 'size_srednja_cm', 'size_velika_cm',
        'sort_order', 'is_active',
    ];

    public const SIZES = ['mala' => 'Mala', 'srednja' => 'Srednja', 'velika' => 'Velika'];

    /** Jedinice mere za veličinu (dimenzija, zapremina, komad…). */
    public const UNITS = [
        'cm' => 'cm',
        'mm' => 'mm',
        'dm' => 'dm',
        'm' => 'm',
        'l' => 'L (litri)',
        'ml' => 'ml',
        'kom' => 'kom',
        'kg' => 'kg',
        'g' => 'g',
    ];

    /** Cena za datu veličinu – iz tabele product_sizes, ili fallback na osnovnu cenu + offset. */
    public function getPriceForSize(string $size): float
    {
        $row = $this->productSizes->firstWhere('size', $size);
        if ($row !== null) {
            return (float) $row->price;
        }
        $offsets = config('app.size_price_offset', ['mala' => -200, 'srednja' => 0, 'velika' => 200]);
        $offset = $offsets[$size] ?? 0;
        return max(0, (float) $this->price + $offset);
    }

    /** Veličina u cm – iz product_sizes, ili iz kolona na products. */
    public function getSizeCm(string $sizeKey): ?string
    {
        $row = $this->productSizes->firstWhere('size', $sizeKey);
        if ($row !== null && $row->size_cm !== null && trim($row->size_cm) !== '') {
            return trim($row->size_cm);
        }
        $col = match ($sizeKey) {
            'mala' => 'size_mala_cm',
            'srednja' => 'size_srednja_cm',
            'velika' => 'size_velika_cm',
            default => null,
        };
        return $col ? $this->{$col} : null;
    }

    /** Naziv veličine sa vrednošću i jedinicom, npr. "Mala - 18 cm" ili "Srednja - 2 L (litri)". */
    public function getSizeLabelWithCm(string $sizeKey): string
    {
        $label = self::SIZES[$sizeKey] ?? $sizeKey;
        $value = $this->getSizeCm($sizeKey);
        if (!$value || trim($value) === '') {
            return $label;
        }
        $value = trim($value);
        $unit = $this->unit ?? 'cm';
        $unitLabel = self::UNITS[$unit] ?? $unit;
        if ($unitLabel && stripos($value, $unit) === false && stripos($value, $unitLabel) === false) {
            $value = $value . ' ' . $unitLabel;
        }
        return $label . ' - ' . $value;
    }

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productSizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translate(?string $locale = null): ?ProductTranslation
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()
            ?? $this->translations()->where('locale', config('app.fallback_locale'))->first();
    }

    public function getNameAttribute(): string
    {
        $t = $this->translate();
        return $t ? $t->name : $this->slug;
    }

    public function getDescriptionAttribute(): ?string
    {
        $t = $this->translate();
        return $t?->description;
    }

    /**
     * Slika se čuva u storage/app/public/products/, u bazi samo relativna putanja (npr. products/abc.jpg).
     * Koristi asset() da URL bude na istom hostu/portu kao zahtev (izbegava ERR_CONNECTION_REFUSED kada APP_URL nema port).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        return asset('storage/' . $this->image);
    }
}
