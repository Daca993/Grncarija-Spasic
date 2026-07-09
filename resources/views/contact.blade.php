@extends('layouts.shop')

@section('title', __('messages.Contact') . ' - ' . config('app.name'))
@section('meta_description', config('app.name') . ' (Grncarija Spale) – kontakt informacije, veleprodaja i upiti.')

@section('content')
    <section class="section-light">
        <div class="site-container text-section" style="max-width: 640px;">
            <h1>{{ __('messages.Contact') }}</h1>
            <p style="margin-bottom: 2rem;">{{ __('messages.contact_subtitle') }}</p>
            <div style="text-align: left;">
                <livewire:contact-form />
            </div>
        </div>
    </section>
@endsection
