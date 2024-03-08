<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SpotifyService;
use App\Services\YouTubeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SpotifyService::class, function ($app) {
            return new SpotifyService();
        });
    
        $this->app->bind(YouTubeService::class, function ($app) {
            return new YouTubeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
