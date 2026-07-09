<nav x-data="{ open: false }" class="relative bg-etno-beige/95 border-b border-etno-brown/80 shadow-kafana">
    <!-- Primary Navigation Menu -->
    <div class="site-container">
        <div class="flex justify-between h-14">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-etno-dark">{{ config('app.name') }}</a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('messages.Dashboard') }}
                    </x-nav-link>
                    @if(Auth::user()->role !== 'admin')
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('messages.Home') }}
                        </x-nav-link>
                    @endif
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            {{ __('messages.Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*') || request()->routeIs('admin.product')">
                            {{ __('messages.Product') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.order')" :active="request()->routeIs('admin.orders.*') || request()->routeIs('admin.order')">
                            {{ __('messages.Orders') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-base leading-5 font-medium rounded-4 text-etno-brown bg-etno-beige/95 hover:text-etno-terracotta focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('messages.Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('messages.Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (mobilni – veći, uočljiviji) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-3 rounded-4 text-etno-brown hover:text-etno-terracotta hover:bg-etno-sand focus:outline-none focus:ring-2 focus:ring-etno-clay focus:ring-offset-2 transition" aria-label="{{ __('messages.Menu') }}">
                    <svg x-show="!open" class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" x-cloak class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (mobilni – posredi, veći, čitljiviji) -->
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.outside="open = false"
         class="sm:hidden absolute left-0 right-0 top-full bg-etno-beige border-b border-etno-brown/80 shadow-lg z-40">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex flex-col items-center gap-1 text-center">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('messages.Dashboard') }}
                </x-responsive-nav-link>
                @if(Auth::user()->role !== 'admin')
                    <x-responsive-nav-link :href="route('home')">{{ __('messages.Home') }}</x-responsive-nav-link>
                @endif
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">{{ __('messages.Categories') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*') || request()->routeIs('admin.product')">{{ __('messages.Product') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.order')" :active="request()->routeIs('admin.orders.*') || request()->routeIs('admin.order')">{{ __('messages.Orders') }}</x-responsive-nav-link>
                @endif
            </div>

            <div class="pt-4 mt-4 border-t border-etno-clay/40 text-center">
                <div class="text-etno-dark font-medium text-base">{{ Auth::user()->name }}</div>
                <div class="text-etno-brown text-sm mt-0.5">{{ Auth::user()->email }}</div>
                <div class="mt-3 flex flex-col items-center gap-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('messages.Profile') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}" class="w-full max-w-xs">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('messages.Logout') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
