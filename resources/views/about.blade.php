@extends('layouts.shop')

@section('title', __('messages.About') . ' - ' . config('app.name'))

@section('content')
<div class="max-w-2xl mx-auto kafana-panel rounded-4 bg-etno-sand/40 p-8">
    <h1 class="text-3xl font-semibold text-etno-dark mb-6 text-center">{{ __('messages.About') }}</h1>
    <h2 class="text-xl font-medium text-etno-brown mb-4">{{ __('messages.about_heading') }}</h2>
    <p class="text-etno-brown leading-relaxed mb-4">{{ __('messages.about_intro') }}</p>
    <p class="text-etno-brown leading-relaxed mb-8">{{ __('messages.about_text') }}</p>
    <div class="kafana-line my-6"></div>
    <p class="text-center">
        <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 bg-etno-terracotta text-white rounded-4 shadow-kafana hover:bg-etno-rust transition font-medium border-2 border-etno-wood/50">{{ __('messages.about_cta') }}</a>
    </p>
</div>
@endsection
