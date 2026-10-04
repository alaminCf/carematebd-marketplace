<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang')
            ?? session('locale')
            ?? $request->cookie('caremate_locale')
            ?? config('app.locale', 'en');

        if (in_array($locale, ['en', 'bn'], true)) {
            App::setLocale($locale);
            if ($request->has('lang')) {
                session(['locale' => $locale]);
                cookie()->queue(cookie()->forever('caremate_locale', $locale));
            }
        }

        return $next($request);
    }
}
