<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (is_dir(base_path('public_html'))) {
            $this->app->usePublicPath(base_path('public_html'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.caremate');
        Paginator::defaultSimpleView('vendor.pagination.caremate');

        View::composer('*', function ($view): void {
            $view->with('siteSettings', Setting::contactInfo());
        });
    }
}
