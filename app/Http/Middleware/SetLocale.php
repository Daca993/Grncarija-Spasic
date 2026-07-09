<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locales = config('app.available_locales', ['en']);
        $locale = $request->get('locale');
        if ($locale && in_array($locale, $locales, true)) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        } elseif (session('locale') && in_array(session('locale'), $locales, true)) {
            App::setLocale(session('locale'));
        }
        return $next($request);
    }
}
