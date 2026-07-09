<?php

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Product;
use Livewire\Component;

new class extends Component
{
    public ?string $categorySlug = null;

    public function mount(?string $category = null): void
    {
        $this->categorySlug = $category;
    }

    public function getCategoriesProperty()
    {
        return Category::where('is_active', true)->orderBy('sort_order')->with('translations')->get();
    }

    public function getProductsProperty()
    {
        $query = Product::where('is_active', true)->with(['category.translations', 'translations', 'productSizes'])->orderBy('sort_order');
        if ($this->categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->categorySlug));
        }
        return $query->get();
    }
};
?>

<div>
    <h1 class="text-2xl font-semibold text-etno-dark mb-4">{{ __('messages.Products') }}</h1>

    @if($this->categories->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-full text-sm font-medium transition {{ !$categorySlug ? 'bg-etno-terracotta text-white' : 'bg-etno-sand text-etno-brown hover:bg-etno-clay/30' }}">Sve</a>
            @foreach($this->categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $categorySlug === $cat->slug ? 'bg-etno-terracotta text-white' : 'bg-etno-sand text-etno-brown hover:bg-etno-clay/30' }}">{{ $cat->name }}</a>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($this->products as $product)
            @php
                $defaultSize = $product->productSizes->contains('size', 'srednja')
                    ? 'srednja'
                    : ($product->productSizes->first()?->size ?? array_key_first(\App\Domain\Catalog\Models\Product::SIZES));
            @endphp
            <div class="kafana-panel bg-white rounded-4 overflow-hidden shadow-kafana hover:shadow-xl hover:-translate-y-1 transition">
                <a href="{{ route('products.show', [$product->category->slug, $product->slug]) }}">
                    <img src="{{ $product->image_url ?? config('app.default_product_image') }}" alt="{{ $product->name }}" class="w-full h-44 object-cover">
                </a>
                <div class="p-3">
                    <h2 class="text-sm font-semibold text-etno-dark"><a href="{{ route('products.show', [$product->category->slug, $product->slug]) }}" class="hover:text-etno-terracotta transition">{{ $product->name }}</a></h2>
                    <p class="text-etno-terracotta font-medium mt-1 product-price" data-mala="{{ number_format($product->getPriceForSize('mala'), 0) }}" data-srednja="{{ number_format($product->getPriceForSize('srednja'), 0) }}" data-velika="{{ number_format($product->getPriceForSize('velika'), 0) }}"><span class="price-num">{{ number_format($product->getPriceForSize($defaultSize), 0) }}</span> {{ $product->currency }}</p>
                    <form action="{{ route('cart.store') }}" method="POST" class="mt-3 product-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-2">
                            <label class="block text-xs text-etno-brown mb-0.5">{{ __('messages.Size') }}</label>
                            <select name="size" class="product-size w-full border border-etno-clay/40 rounded-4 px-2 py-1.5 bg-etno-cream text-etno-dark text-sm focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition">
                                @foreach(\App\Domain\Catalog\Models\Product::SIZES as $key => $label)
                                    <option value="{{ $key }}" @selected($key === $defaultSize)>{{ $product->getSizeLabelWithCm($key) }} — {{ number_format($product->getPriceForSize($key), 0) }} {{ $product->currency }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full py-2 bg-etno-terracotta text-white rounded-full hover:bg-etno-rust transition font-medium">{{ __('messages.Add to cart') }}</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-etno-brown/80">{{ __('messages.Products') }} — nema proizvoda.</p>
        @endforelse
    </div>
</div>
<script>
document.body.addEventListener('change', function(e) {
    if (!e.target.matches('.product-size')) return;
    var card = e.target.closest('.kafana-panel');
    if (!card) return;
    var priceEl = card.querySelector('.product-price');
    var size = e.target.value;
    if (priceEl && priceEl.dataset[size] !== undefined) {
        var num = priceEl.querySelector('.price-num');
        if (num) num.textContent = priceEl.dataset[size];
    }
});
</script>
