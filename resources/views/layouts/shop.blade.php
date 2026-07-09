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
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app-shop.js'])
    @livewireStyles
    <style>
        /* Hero – gradient overlay + slika (bez ::before) */
        .hero { background-image: linear-gradient(rgba(30, 22, 17, 0.45), rgba(30, 22, 17, 0.45)), url('{{ asset('assets/img/hero.png') }}'); }
    </style>
</head>
<body class="body-texture text-etno-dark min-h-screen antialiased">
    <div class="etno-border-top"></div>
    <header x-data="{ mobileMenuOpen: false }" class="bg-etno-cream/90 backdrop-blur sticky top-0 z-50 border-b border-etno-clay/30">
        <div class="site-container flex items-center justify-between h-16 gap-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                <span class="etno-logo-icon" aria-hidden="true"></span>
                <span class="text-base font-semibold text-etno-dark tracking-wide">{{ config('app.name') }}</span>
            </a>

            {{-- Desktop nav – odvojeno od loga --}}
            <nav class="hidden md:flex items-center gap-6 flex-1 justify-end">
                <a href="{{ route('home') }}" class="text-etno-brown hover:text-etno-terracotta transition">{{ __('messages.Home') }}</a>
                <a href="{{ route('products.index') }}" class="text-etno-brown hover:text-etno-terracotta transition">{{ __('messages.Products') }}</a>
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

    <footer class="border-t border-etno-clay/30 py-10 bg-etno-beige/60 mt-16">
        <div class="site-container">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-etno-brown/90">
                <div class="text-center md:text-left">
                    <span class="text-etno-dark font-medium">{{ __('messages.Copyright') }}</span>
                    <span class="ml-2">&copy; {{ date('Y') }} {{ config('app.name') }}.</span>
                    <span class="ml-2 text-etno-brown/70">{{ __('messages.All rights reserved') }}</span>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4">
                    <span class="text-etno-dark font-medium">{{ __('messages.Contact') }}</span>
                    @if(config('app.contact_phone'))
                        <a class="inline-flex items-center gap-2 hover:text-etno-terracotta transition" href="tel:{{ preg_replace('/\\s+/', '', (string) config('app.contact_phone')) }}">
                            <svg class="w-4 h-4 text-etno-brown" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7.5 4.5h3l1.5 4.5-2.25 1.5c1.2 2.4 3.15 4.35 5.55 5.55L16.8 13.8 21.3 15.3v3c0 .8-.5 1.5-1.3 1.7-1.6.4-3.3.6-5 .6C9.4 21.6 2.4 14.6 2.4 6c0-1.7.2-3.4.6-5 .2-.8.9-1.3 1.7-1.3h2.8z" fill="currentColor"/>
                            </svg>
                            <span>{{ config('app.contact_phone') }}</span>
                        </a>
                    @endif
                    @if(config('app.contact_email'))
                        <a class="inline-flex items-center gap-2 hover:text-etno-terracotta transition" href="mailto:{{ config('app.contact_email') }}">
                            <svg class="w-4 h-4 text-etno-brown" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11A2.5 2.5 0 0 1 17.5 20h-11A2.5 2.5 0 0 1 4 17.5v-11zm2.2-.4 5.8 4.3 5.8-4.3H6.2zm11.6 2.7-5.3 4a1 1 0 0 1-1.2 0l-5.3-4V17.5c0 .3.2.5.5.5h11a.5.5 0 0 0 .5-.5V8.8z" fill="currentColor"/>
                            </svg>
                            <span>{{ config('app.contact_email') }}</span>
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @if(config('app.instagram_url'))
                        <a href="{{ config('app.instagram_url') }}" target="_blank" rel="noopener noreferrer"
                           class="w-10 h-10 rounded-full border border-etno-clay/40 bg-etno-cream flex items-center justify-center hover:bg-etno-sand transition"
                           aria-label="Instagram">
                            <svg class="w-5 h-5 text-etno-brown" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7.8 2.8h8.4A5 5 0 0 1 21.2 7.8v8.4a5 5 0 0 1-5 5H7.8a5 5 0 0 1-5-5V7.8a5 5 0 0 1 5-5zm0 2A3 3 0 0 0 4.8 7.8v8.4a3 3 0 0 0 3 3h8.4a3 3 0 0 0 3-3V7.8a3 3 0 0 0-3-3H7.8zm4.2 3.5a5.7 5.7 0 1 1 0 11.4 5.7 5.7 0 0 1 0-11.4zm0 2a3.7 3.7 0 1 0 0 7.4 3.7 3.7 0 0 0 0-7.4zm6.3-2.6a1.3 1.3 0 1 1 0 2.6 1.3 1.3 0 0 1 0-2.6z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if(config('app.facebook_url'))
                        <a href="{{ config('app.facebook_url') }}" target="_blank" rel="noopener noreferrer"
                           class="w-10 h-10 rounded-full border border-etno-clay/40 bg-etno-cream flex items-center justify-center hover:bg-etno-sand transition"
                           aria-label="Facebook">
                            <svg class="w-5 h-5 text-etno-brown" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M13.9 21v-7h2.4l.4-2.8h-2.8V9.4c0-.8.2-1.4 1.4-1.4h1.5V5.5c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.5-4 4.2v1.6H6.9V14h2.5v7h4.5z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
