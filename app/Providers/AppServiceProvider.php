<?php

namespace App\Providers;

use App\Domain\Cart\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->useLangPath(resource_path('lang'));
    }

    public function boot(): void
    {
        View::composer(['layouts.shop', 'layouts.guest'], function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
