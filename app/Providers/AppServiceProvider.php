<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Supaya asset()/vite() mengarah ke htdocs/ (bukan htdocs/laravel/public yang sudah dipindah)
        if ($this->app->environment('production')) {
            $this->app->bind('path.public', function () {
                return base_path('../');
            });
        }
    }

  public function boot(): void
    {
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
