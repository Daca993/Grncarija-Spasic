@php
    $seoTitle = trim($__env->yieldContent('title', config('app.name')));
    $seoDescription = trim($__env->yieldContent(
        'meta_description',
        config('app.seo_description', config('app.name') . ' - ručno rađena grnčarija (grncarija) i keramika za veleprodaju.')
    ));
    $seoDescription = preg_replace('/\s+/u', ' ', $seoDescription);

    $canonical = trim($__env->yieldContent('canonical', url()->current()));
    $robots = trim($__env->yieldContent('robots', 'index,follow'));
    $ogType = trim($__env->yieldContent('og_type', 'website'));
    $ogImage = trim($__env->yieldContent('og_image', asset('assets/img/hero.png')));

    $sameAs = array_values(array_filter([
        config('app.instagram_url'),
        config('app.facebook_url'),
    ]));

    $orgSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => config('app.name'),
        'url' => rtrim((string) config('app.url', url('/')), '/'),
        'telephone' => config('app.contact_phone') ?: null,
        'email' => config('app.contact_email') ?: null,
        'sameAs' => $sameAs ?: null,
    ];
    $orgSchema = array_filter($orgSchema, fn ($v) => $v !== null);
@endphp

<meta name="description" content="{{ $seoDescription }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@yield('jsonld')
