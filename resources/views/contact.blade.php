@extends('layouts.shop')

@section('title', __('messages.Contact') . ' - ' . config('app.name'))
@section('meta_description', config('app.name') . ' (Grncarija Spale) – kontakt informacije, veleprodaja i upiti.')

@section('content')
    <div class="site-container py-6 md:py-10">
        <livewire:contact-form />
    </div>
@endsection
