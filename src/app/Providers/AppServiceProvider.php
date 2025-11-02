<?php

namespace App\Providers;

use App\OpenCode;
use Illuminate\Support\ServiceProvider;
use Laravel\Boost\Boost;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Boost::registerCodeEnvironment('opencode', OpenCode::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
