<?php

namespace App\Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['slug', 'image', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function translate(?string $locale = null): ?CategoryTranslation
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
     * Slika se čuva u storage/app/public/categories/, u bazi samo relativna putanja (npr. categories/abc.jpg).
     * Koristi asset() da URL bude na istom hostu/portu kao zahtev.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        return asset('storage/' . $this->image);
    }
}
