<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('vote-submit', function (Request $request) {
            return [
                Limit::perMinute(20)->by((string) optional($request->user())->id ?: $request->ip()),
                Limit::perMinute(60)->by($request->ip()),
            ];
        });

        RateLimiter::for('invite-create', function (Request $request) {
            return [
                Limit::perHour(60)->by((string) optional($request->user())->id ?: $request->ip()),
                Limit::perHour(200)->by($request->ip()),
            ];
        });

        RateLimiter::for('results-live', function (Request $request) {
            return [
                Limit::perMinute(180)->by((string) optional($request->user())->id ?: $request->ip()),
            ];
        });
    }
}
