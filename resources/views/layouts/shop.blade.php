<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @include('partials.seo')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Work+Sans:wght@400;500;600&family=Caveat:wght@600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app-shop.js'])
    @livewireStyles
    <style>
        /* Hero – gradient overlay + slika (bez ::before) */
        .hero { background-image: linear-gradient(rgba(30, 22, 17, 0.45), rgba(30, 22, 17, 0.45)), url('{{ asset('assets/img/hero.png') }}'); }
    </style>
</head>
<body class="body-texture text-etno-dark min-h-screen antialiased">
    <div class="etno-border-top"></div>
    <header data-site-header x-data="{ mobileMenuOpen: false }" class="bg-etno-cream/90 backdrop-blur sticky top-0 z-50 border-b border-etno-clay/30 transition-colors duration-300">
        <div class="site-container flex items-center justify-between h-16 gap-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                <span class="etno-logo-icon" aria-hidden="true"></span>
                <span class="text-base font-semibold text-etno-dark tracking-wide">{{ config('app.name') }}</span>
            </a>

            {{-- Desktop nav – odvojeno od loga --}}
            <nav class="hidden md:flex items-center gap-6 flex-1 justify-end">
                <a href="{{ route('home') }}" class="text-etno-brown hover:text-etno-terracotta transition">{{ __('messages.Home') }}</a>
                <a href="{{ route('products.index') }}" class="text-etno-brown hover:text-etno-terracotta transition">{{ __('messages.Products') }}</a>
                <a href="{{ route('home') }}#kontakt" class="text-etno-brown hover:text-etno-terracotta transition">{{ __('messages.nav_custom_order') }}</a>
                <a href="{{ route('cart.index') }}" class="text-etno-brown hover:text-etno-terracotta transition flex items-center gap-1">
                    {{ __('messages.Cart') }}
                    @if(isset($cartCount) && $cartCount > 0)
                        <span class="bg-etno-terracotta text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-etno-brown hover:text-etno-terracotta transition">{{ __('messages.Logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-etno-brown hover:text-etno-terracotta transition">{{ __('messages.Login') }}</a>
                    <a href="{{ route('register') }}" class="border border-etno-clay text-etno-brown px-4 py-2 rounded-full hover:bg-etno-sand transition">{{ __('messages.Register') }}</a>
                @endauth
                <a href="{{ route('contact') }}" class="px-4 py-2 rounded-full font-medium bg-etno-terracotta text-white hover:bg-etno-rust transition">{{ __('messages.Contact us') }}</a>
                <livewire:locale-switcher />
            </nav>

            {{-- Hamburger (samo mobilni) --}}
            <div class="md:hidden flex items-center gap-2">
                <livewire:locale-switcher />
                <button @click="mobileMenuOpen = ! mobileMenuOpen" type="button" class="p-2.5 rounded-full text-etno-brown hover:bg-etno-sand hover:text-etno-terracotta focus:outline-none focus:ring-2 focus:ring-etno-clay" aria-label="{{ __('messages.Menu') }}">
                    <svg x-show="!mobileMenuOpen" class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobilni meni (ispadajući) --}}
        <div x-show="mobileMenuOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             @click.outside="mobileMenuOpen = false"
             class="md:hidden absolute left-0 right-0 top-full bg-etno-cream border-b border-etno-clay/30 shadow-lg z-40">
            <nav class="site-container py-4 flex flex-col gap-1 text-center">
                <a href="{{ route('home') }}" class="py-3 text-etno-dark font-medium text-lg border-b border-etno-clay/20 hover:bg-etno-sand/50 rounded-4">{{ __('messages.Home') }}</a>
                <a href="{{ route('products.index') }}" class="py-3 text-etno-dark font-medium text-lg border-b border-etno-clay/20 hover:bg-etno-sand/50 rounded-4">{{ __('messages.Products') }}</a>
                <a href="{{ route('home') }}#kontakt" class="py-3 text-etno-dark font-medium text-lg border-b border-etno-clay/20 hover:bg-etno-sand/50 rounded-4">{{ __('messages.nav_custom_order') }}</a>
                <a href="{{ route('cart.index') }}" class="py-3 text-etno-dark font-medium text-lg border-b border-etno-clay/20 hover:bg-etno-sand/50 rounded-4 flex items-center justify-center gap-2">
                    {{ __('messages.Cart') }}
                    @if(isset($cartCount) && $cartCount > 0)
                        <span class="bg-etno-terracotta text-white text-sm rounded-full min-w-[1.5rem] h-6 px-1.5 flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="border-b border-etno-clay/20">
                        @csrf
                        <button type="submit" class="w-full py-3 text-etno-dark font-medium text-lg hover:bg-etno-sand/50 rounded-4">{{ __('messages.Logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="py-3 text-etno-dark font-medium text-lg border-b border-etno-clay/20 hover:bg-etno-sand/50 rounded-4">{{ __('messages.Login') }}</a>
                    <a href="{{ route('register') }}" class="py-3 text-etno-dark font-medium text-lg border-b border-etno-clay/20 hover:bg-etno-sand/50 rounded-4">{{ __('messages.Register') }}</a>
                @endauth
                <a href="{{ route('contact') }}" class="py-3 rounded-full font-medium text-lg bg-etno-terracotta text-white hover:bg-etno-rust">{{ __('messages.Contact us') }}</a>
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div class="site-container py-2">
            <p class="bg-green-100 text-green-800 px-4 py-2 rounded-4 border border-green-200">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('info'))
        <div class="site-container py-2">
            <p class="bg-etno-sand text-etno-rust px-4 py-2 rounded-4 border border-etno-clay/40">{{ session('info') }}</p>
        </div>
    @endif

    <main class="w-full">
        @yield('content')
    </main>

    <div class="etno-border-bottom"></div>
    <footer style="background: var(--color-dark); padding: 2rem 0; text-align: center;">
        <p style="color: rgba(255,255,255,0.7); font-size: 0.875rem; margin: 0;">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.All rights reserved') }}</p>
    </footer>
    @livewireScripts
</body>
</html>
