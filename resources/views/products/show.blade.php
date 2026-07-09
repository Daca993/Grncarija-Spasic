@extends('layouts.shop')

@section('title', $product->name . ' - ' . config('app.name'))
@section('meta_description', $product->name . ' – ' . config('app.name') . ' (Grncarija Spale). Ručno rađena grnčarija i keramika za veleprodaju.')

@section('og_type', 'product')
@section('og_image', $product->image_url ?? config('app.default_product_image'))
@section('jsonld')
    @php
        $pSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => [$product->image_url ?? config('app.default_product_image')],
            'description' => $product->description ? preg_replace('/\s+/u', ' ', trim($product->description)) : null,
            'brand' => [
                '@type' => 'Brand',
                'name' => config('app.name'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => $product->currency ?: 'RSD',
                'price' => (string) ((float) $product->price),
                'availability' => 'https://schema.org/InStock',
                'url' => url()->current(),
            ],
        ];
        $pSchema = array_filter($pSchema, fn ($v) => $v !== null);
    @endphp
    <script type="application/ld+json">{!! json_encode($pSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@section('content')
<div class="site-container">
    <nav class="text-sm text-etno-brown mb-4">
        <a href="{{ route('products.index') }}" class="hover:text-etno-terracotta transition">{{ __('messages.Products') }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-etno-terracotta transition">{{ $product->category->name }}</a>
        <span class="mx-1">/</span>
        <span class="text-etno-dark">{{ $product->name }}</span>
    </nav>
    <div class="grid md:grid-cols-2 gap-8 kafana-panel bg-white rounded-4 overflow-hidden shadow-kafana">
        <div>
            <img src="{{ $product->image_url ?? config('app.default_product_image') }}" alt="{{ $product->name }}" class="w-full h-auto object-cover">
        </div>
        <div class="p-8 flex flex-col justify-center">
            <h1 class="text-2xl font-semibold text-etno-dark">{{ $product->name }}</h1>
            @if($product->description)
                <p class="text-etno-brown mt-4">{{ $product->description }}</p>
            @endif
            @php
                $sizesWithCm = collect(\App\Domain\Catalog\Models\Product::SIZES)->map(fn ($label, $key) => $product->getSizeLabelWithCm($key))->filter();
                $defaultSize = $product->productSizes->contains('size', 'srednja')
                    ? 'srednja'
                    : ($product->productSizes->first()?->size ?? array_key_first(\App\Domain\Catalog\Models\Product::SIZES));
            @endphp
            @if($sizesWithCm->isNotEmpty())
                <p class="text-etno-brown mt-3 text-sm">{{ __('messages.Size') }}: {{ $sizesWithCm->implode(', ') }}</p>
            @endif
            <p class="text-xl text-etno-terracotta font-semibold mt-3"><span id="product-price">{{ number_format($product->getPriceForSize($defaultSize), 0) }}</span> {{ $product->currency }}</p>
            @if(config('app.wholesale_only'))
                <div class="mt-3 kafana-panel bg-etno-sand/70 rounded-4 p-3 text-etno-brown text-sm">
                    <p class="font-semibold text-etno-dark">{{ __('messages.wholesale_notice_title') }}</p>
                    <p class="mt-1">
                        {{ __('messages.wholesale_notice_body', ['min' => number_format((int) config('app.wholesale_min_total', 70000), 0)]) }}
                    </p>
                </div>
            @endif
            <form action="{{ route('cart.store') }}" method="POST" class="mt-6 space-y-3" id="product-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div>
                    <label class="block text-sm font-medium text-etno-brown mb-1">{{ __('messages.Size') }}</label>
                    <select name="size" id="product-size" class="w-full border border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition">
                        @foreach(\App\Domain\Catalog\Models\Product::SIZES as $key => $label)
                            <option value="{{ $key }}" data-price="{{ number_format($product->getPriceForSize($key), 0) }}" @selected($key === $defaultSize)>{{ $product->getSizeLabelWithCm($key) }} — {{ number_format($product->getPriceForSize($key), 0) }} {{ $product->currency }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <input type="number" name="quantity" value="1" min="1" max="99" class="w-20 border border-etno-clay/40 rounded-4 px-2 py-2 bg-etno-cream text-etno-dark focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition">
                    <button type="submit" class="flex-1 py-2 bg-etno-terracotta text-white rounded-full shadow-md hover:bg-etno-rust transition font-medium">{{ __('messages.Add to cart') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
(() => {
    const sizeSelect = document.getElementById('product-size');
    const priceEl = document.getElementById('product-price');
    if (!sizeSelect || !priceEl) return;

    const syncPrice = () => {
        const opt = sizeSelect.options[sizeSelect.selectedIndex];
        const price = opt?.getAttribute('data-price');
        if (price !== null && price !== undefined) {
            priceEl.textContent = price;
        }
    };

    sizeSelect.addEventListener('change', syncPrice);
    syncPrice();
})();
</script>
@endsection
