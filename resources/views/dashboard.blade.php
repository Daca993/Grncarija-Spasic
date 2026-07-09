<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-etno-dark leading-tight">
            {{ __('messages.Dashboard') }}
        </h2>
    </x-slot>

    <section class="py-6 md:py-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="rustic-card kafana-panel bg-white overflow-hidden shadow-kafana rounded-4 p-6">
                    <p class="text-sm font-medium text-etno-brown uppercase tracking-wide">{{ __('messages.dashboard_stat_products') }}</p>
                    <p class="mt-3 text-3xl font-semibold text-etno-dark">{{ $productsCount }}</p>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.products.index') }}" class="mt-3 inline-block text-sm text-etno-terracotta hover:text-etno-rust">{{ __('messages.Products') }} →</a>
                        @endif
                    @endauth
                </div>
                <div class="rustic-card kafana-panel bg-white overflow-hidden shadow-kafana rounded-4 p-6">
                    <p class="text-sm font-medium text-etno-brown uppercase tracking-wide">{{ __('messages.dashboard_stat_categories') }}</p>
                    <p class="mt-3 text-3xl font-semibold text-etno-dark">{{ $categoriesCount }}</p>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.categories.index') }}" class="mt-3 inline-block text-sm text-etno-terracotta hover:text-etno-rust">{{ __('messages.Categories') }} →</a>
                        @endif
                    @endauth
                </div>
                <div class="rustic-card kafana-panel bg-white overflow-hidden shadow-kafana rounded-4 p-6">
                    <p class="text-sm font-medium text-etno-brown uppercase tracking-wide">{{ __('messages.dashboard_stat_orders') }}</p>
                    <p class="mt-3 text-3xl font-semibold text-etno-dark">{{ $ordersCount }}</p>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.order') }}" class="mt-3 inline-block text-sm text-etno-terracotta hover:text-etno-rust">{{ __('messages.Orders') }} →</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
