@extends('layouts.shop')

@section('title', __('messages.Checkout') . ' - ' . config('app.name'))
@section('robots', 'noindex,nofollow')

@section('content')
    <div class="site-container py-6 md:py-8">
        <livewire:checkout-form />
    </div>
@endsection
