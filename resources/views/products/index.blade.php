@extends('layouts.shop')

@section('title', __('messages.Products') . ' - ' . config('app.name'))
@section('meta_description', config('app.name') . ' (Grncarija Spale) – katalog proizvoda. Ručno rađena grnčarija i keramika za veleprodaju.')

@section('content')
    <div class="site-container py-6 md:py-8">
        <livewire:product-list :category="request('category')" />
    </div>
@endsection
