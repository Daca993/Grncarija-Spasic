@extends('layouts.shop')

@section('title', config('app.name') . ' - ' . __('messages.Home'))
@section('meta_description', config('app.name') . ' – Ručno rađena grnčarija iz Srbije. Tradicionalna keramika sa ljubavlju.')

@section('content')

{{-- HERO --}}
<section class="hero" style="background-image: url('{{ asset('assets/img/hero.png') }}');">
    <div class="hero-content">
        <h1>{{ __('messages.hero_title') }}</h1>
        <p>{{ __('messages.hero_subtitle') }}</p>
        <a href="{{ route('products.index') }}" class="btn-primary">{{ __('messages.hero_cta') }}</a>
    </div>
</section>

{{-- O NAMA --}}
<section class="section-light">
    <div class="site-container">
        <div class="about-grid">
            <div class="about-image">
                <img src="{{ asset('assets/img/about.png') }}" alt="{{ __('messages.o_nama_heading') }}">
            </div>
            <div class="about-text">
                <h2>{{ __('messages.o_nama_heading') }}</h2>
                @php
                    $rawAbout = trim(__('messages.o_nama_paragraph_1'));
                    $rawAbout = preg_replace("/\\R\\s*\\R+/u", "\n\n", $rawAbout);
                    $rawAbout = preg_replace("/(?<!\\n)\\n(?!\\n)/u", ' ', $rawAbout);
                    $aboutParagraphs = array_values(array_filter(
                        preg_split("/\\n\\n+/u", $rawAbout) ?: [],
                        fn ($p) => trim((string) $p) !== ''
                    ));
                @endphp
                @foreach($aboutParagraphs as $p)
                    <p>{{ $p }}</p>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- KAKO NASTAJE --}}
<section class="section-dark">
    <div class="site-container">
        <div class="text-section">
            <h2>{{ __('messages.kako_nastaje_heading') }}</h2>
            <p>{{ __('messages.kako_nastaje_intro') }}</p>
        </div>

        <div class="process-steps">
            <div class="step">
                <div class="step-number">01</div>
                <h3 class="step-title">{{ __('messages.step_oblikovanje') }}</h3>
                <img src="{{ asset('assets/img/process-oblikovanje.png') }}" alt="{{ __('messages.step_oblikovanje') }}" class="step-image">
                <p class="step-desc">{{ __('messages.step_oblikovanje_desc') }}</p>
            </div>

            <div class="step">
                <div class="step-number">02</div>
                <h3 class="step-title">{{ __('messages.step_pecenje') }}</h3>
                <img src="{{ asset('assets/img/process-pecenje.png') }}" alt="{{ __('messages.step_pecenje') }}" class="step-image">
                <p class="step-desc">{{ __('messages.step_pecenje_desc') }}</p>
            </div>

            <div class="step">
                <div class="step-number">03</div>
                <h3 class="step-title">{{ __('messages.step_glaziranje') }}</h3>
                <img src="{{ asset('assets/img/process-glazura.png') }}" alt="{{ __('messages.step_glaziranje') }}" class="step-image">
                <p class="step-desc">{{ __('messages.step_glaziranje_desc') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- PROIZVODI --}}
<section class="section-light">
    <div class="site-container">
        <div class="text-section">
            <h2>{{ __('messages.featured_products') }}</h2>
            <p>Odaberite proizvode iz naše kolekcije</p>
        </div>

        <div class="product-grid">
            @forelse($featuredProducts as $product)
                <a href="{{ route('products.show', [$product->category->slug ?? 'uncategorized', $product->slug]) }}" class="product-card">
                    <img src="{{ $product->image_url ?? config('app.default_product_image') }}" alt="{{ $product->name }}">
                    <div class="product-card-body">
                        <h3 class="product-card-name">{{ $product->name }}</h3>
                        <p class="product-card-price">{{ number_format((float) $product->price, 0) }} RSD</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center py-12">{{ __('messages.Coming soon') }}</p>
            @endforelse
        </div>

        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('products.index') }}" class="btn-secondary">Pogledaj sve proizvode</a>
        </div>
    </div>
</section>

{{-- KATEGORIJE --}}
<section class="section-dark">
    <div class="site-container">
        <div class="text-section">
            <h2 style="color: white;">{{ __('messages.our_categories') }}</h2>
        </div>

        <div class="product-grid">
            @forelse($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="product-card">
                    @php
                        $catImg = $category->products->first()?->image_url ?? $category->image_url ?? config('app.default_category_image');
                    @endphp
                    <img src="{{ $catImg }}" alt="{{ $category->name }}">
                    <div class="product-card-body">
                        <h3 class="product-card-name">{{ $category->name }}</h3>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center py-12" style="color: white;">{{ __('messages.Coming soon') }}</p>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section-light">
    <div class="site-container">
        <div class="text-section">
            <h2>Želiš da saznašš više?</h2>
            <p style="font-size: 1rem; margin: 1.5rem 0;">Kontaktiraj nas ili poseti našu radionicu</p>
            <a href="{{ route('contact') }}" class="btn-primary">Pošalji poruku</a>
        </div>
    </div>
</section>

@endsection
