@extends('layouts.shop')

@section('title', __('messages.Cart') . ' - ' . config('app.name'))
@section('robots', 'noindex,nofollow')

@section('content')
    <div class="site-container py-6 md:py-8">
        <livewire:cart-list />
    </div>
@endsection
