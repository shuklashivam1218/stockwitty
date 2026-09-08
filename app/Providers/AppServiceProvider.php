<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The admin panel is a Bootstrap 5 theme and never loads Tailwind CSS,
        // so Laravel's default `pagination::tailwind` view renders with dead
        // utility classes — bare "Showing X to Y" text and unstyled prev/next
        // links. Bootstrap 5's pagination view matches the CSS actually loaded.
        Paginator::useBootstrapFive();

        // www.stockswitty.com is the canonical, https-only host (enforced in
        // public/.htaccess). Force the scheme here too so url()/route() and
        // the canonical tag never emit http:// links if the host's SSL
        // termination doesn't mark the request as secure for PHP.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
