<?php

namespace Goopil\RestFilter;

use Illuminate\Support\ServiceProvider;

/**
 * Class RestScopeProvider
 * @package Goopil\RestFilter
 */
class RestScopeProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/queryScope.php' => config_path('queryScope.php')
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/queryScope.php', 'queryScope');
    }
}
