@extends('layouts.shop')

@section('title', config('app.name') . ' - ' . __('messages.Home'))
@section('meta_description', config('app.name') . ' – Ručno rađena grnčarija iz Srbije. Tradicionalna keramika sa ljubavlju.')

@section('content')

{{-- HERO --}}
<section id="pocetna" class="section-light" style="padding-top: clamp(2.5rem, 8vw, 5rem);">
    <div class="site-container">
        <div class="about-grid">
            <div>
                <span class="etno-eyebrow">{{ __('messages.eyebrow_handmade') }}</span>
                <h1 style="margin-bottom: 1.25rem;">{{ __('messages.hero_title') }}</h1>
                <p style="font-size: 1.125rem; max-width: 32rem; margin-bottom: 2rem;">{{ __('messages.hero_subtitle') }}</p>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('products.index') }}" class="btn-primary">{{ __('messages.hero_cta') }}</a>
                    <a href="#kontakt" class="btn-secondary">{{ __('messages.narudzbina_cta') }}</a>
                </div>
            </div>
            <div class="about-image">
                <img src="{{ asset('assets/img/hero.png') }}" alt="{{ __('messages.hero_title') }}">
            </div>
        </div>
    </div>
</section>

{{-- STATISTIKA --}}
<section class="section-dark">
    <div class="site-container">
        <div class="stats">
            <div class="stat">
                <div class="stat-number">100%</div>
                <div class="stat-label">{{ __('messages.stat_handmade_label') }}</div>
            </div>
            <div class="stat">
                <div class="stat-number">{{ __('messages.stat_generations_number') }}</div>
                <div class="stat-label">{{ __('messages.stat_generations_label') }}</div>
            </div>
            <div class="stat">
                <div class="stat-number">100%</div>
                <div class="stat-label">{{ __('messages.stat_materials_label') }}</div>
            </div>
        </div>
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
                <span class="etno-eyebrow">{{ __('messages.eyebrow_story') }}</span>
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
            <span class="etno-eyebrow">{{ __('messages.eyebrow_craft') }}</span>
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

{{-- PROIZVODI (sa filterom po kategoriji) --}}
<section id="proizvodi" class="section-light">
    <div class="site-container" x-data="{ activeCategory: 'all' }">
        <div class="text-section">
            <span class="etno-eyebrow">{{ __('messages.eyebrow_collection') }}</span>
            <h2>{{ __('messages.Products') }}</h2>
        </div>

        <div style="display: flex; justify-content: center; gap: 0.75rem; margin: 2rem 0 1rem; flex-wrap: wrap;">
            <button
                type="button"
                @click="activeCategory = 'all'"
                :style="activeCategory === 'all' ? 'background: var(--color-primary); color: white; border-color: var(--color-primary);' : 'background: transparent; color: var(--color-dark); border-color: var(--color-border);'"
                style="padding: 0.5rem 1.25rem; border-radius: 999px; border-width: 1.5px; border-style: solid; font-family: 'Work Sans', sans-serif; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease;"
            >{{ __('messages.category_all') }}</button>
            @foreach($categories as $category)
                <button
                    type="button"
                    @click="activeCategory = '{{ $category->slug }}'"
                    :style="activeCategory === '{{ $category->slug }}' ? 'background: var(--color-primary); color: white; border-color: var(--color-primary);' : 'background: transparent; color: var(--color-dark); border-color: var(--color-border);'"
                    style="padding: 0.5rem 1.25rem; border-radius: 999px; border-width: 1.5px; border-style: solid; font-family: 'Work Sans', sans-serif; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease;"
                >{{ $category->name }}</button>
            @endforeach
        </div>

        @if($shopProducts->isEmpty())
            <p class="col-span-full text-center py-12">{{ __('messages.Coming soon') }}</p>
        @else
            <div style="margin-top: 3rem;">
                @foreach($shopProducts->chunk(4) as $rowIndex => $row)
                    @php
                        $rowCategories = $row->map(fn ($p) => $p->category->slug ?? '')->unique()->filter()->values();
                        $rowCondition = "activeCategory === 'all'" . $rowCategories->map(fn ($slug) => " || activeCategory === '{$slug}'")->implode('');
                    @endphp
                    <div class="stack-item product-stack-row"
                         style="top: {{ 88 + $rowIndex * 22 }}px; z-index: {{ $rowIndex + 1 }};"
                         x-show="{{ $rowCondition }}"
                         x-cloak>
                        <div class="product-grid">
                            @foreach($row as $product)
                                <a href="{{ route('products.show', [$product->category->slug ?? 'uncategorized', $product->slug]) }}"
                                   class="product-card"
                                   x-show="activeCategory === 'all' || activeCategory === '{{ $product->category->slug ?? '' }}'"
                                   x-cloak>
                                    <img src="{{ $product->image_url ?? config('app.default_product_image') }}" alt="{{ $product->name }}">
                                    <div class="product-card-body">
                                        @if($product->category)
                                            <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-primary); font-weight: 600; margin-bottom: 0.25rem;">{{ $product->category->name }}</span>
                                        @endif
                                        <h3 class="product-card-name">{{ $product->name }}</h3>
                                        <p class="product-card-price">{{ number_format((float) $product->price, 0) }} RSD</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('products.index') }}" class="btn-secondary">Pogledaj sve proizvode</a>
        </div>
    </div>
</section>

{{-- KONTAKT --}}
<section id="kontakt" class="section-light">
    <div class="site-container grid gap-12 md:grid-cols-2 md:gap-16" style="display: grid;">
        <div>
            <span class="etno-eyebrow">{{ __('messages.eyebrow_coffee') }}</span>
            <h2 style="margin-bottom: 1.5rem;">{{ __('messages.Contact') }}</h2>
            <div style="display: flex; flex-direction: column; gap: 1.125rem; font-size: 1rem; line-height: 1.5;">
                <div>
                    <strong style="font-weight: 600;">{{ __('messages.workshop_label') }}</strong><br>
                    <span style="color: rgba(42,35,32,0.75);">{{ __('messages.workshop_address_value') }}</span>
                </div>
                <div>
                    <strong style="font-weight: 600;">{{ __('messages.workshop_hours_label') }}</strong><br>
                    <span style="color: rgba(42,35,32,0.75);">{{ __('messages.workshop_hours_value') }}</span>
                </div>
                <div>
                    <strong style="font-weight: 600;">{{ __('messages.workshop_phone_email_label') }}</strong><br>
                    <span style="color: rgba(42,35,32,0.75);">
                        @if(config('app.contact_phone'))
                            {{ config('app.contact_phone') }}
                        @endif
                        @if(config('app.contact_phone') && config('app.contact_email'))
                            &middot;
                        @endif
                        @if(config('app.contact_email'))
                            {{ config('app.contact_email') }}
                        @endif
                    </span>
                </div>
                @if(config('app.instagram_url') || config('app.facebook_url'))
                    <div>
                        <strong style="font-weight: 600;">{{ __('messages.workshop_social_label') }}</strong><br>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;">
                    @if(config('app.instagram_url'))
                        <a href="{{ config('app.instagram_url') }}" target="_blank" rel="noopener noreferrer"
                           style="width: 2.5rem; height: 2.5rem; border-radius: 999px; border: 1px solid var(--color-border); background: white; display: flex; align-items: center; justify-content: center;"
                           aria-label="Instagram">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-dark);" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7.8 2.8h8.4A5 5 0 0 1 21.2 7.8v8.4a5 5 0 0 1-5 5H7.8a5 5 0 0 1-5-5V7.8a5 5 0 0 1 5-5zm0 2A3 3 0 0 0 4.8 7.8v8.4a3 3 0 0 0 3 3h8.4a3 3 0 0 0 3-3V7.8a3 3 0 0 0-3-3H7.8zm4.2 3.5a5.7 5.7 0 1 1 0 11.4 5.7 5.7 0 0 1 0-11.4zm0 2a3.7 3.7 0 1 0 0 7.4 3.7 3.7 0 0 0 0-7.4zm6.3-2.6a1.3 1.3 0 1 1 0 2.6 1.3 1.3 0 0 1 0-2.6z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if(config('app.facebook_url'))
                        <a href="{{ config('app.facebook_url') }}" target="_blank" rel="noopener noreferrer"
                           style="width: 2.5rem; height: 2.5rem; border-radius: 999px; border: 1px solid var(--color-border); background: white; display: flex; align-items: center; justify-content: center;"
                           aria-label="Facebook">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-dark);" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M13.9 21v-7h2.4l.4-2.8h-2.8V9.4c0-.8.2-1.4 1.4-1.4h1.5V5.5c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.5-4 4.2v1.6H6.9V14h2.5v7h4.5z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div>
            <livewire:contact-form />
        </div>
    </div>
</section>

@endsection
