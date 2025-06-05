<?php

namespace Proghasan\BladeFormValidator;

use Illuminate\Support\ServiceProvider;

class BladeFormValidateServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method merges the package's default configuration file
     * with the application's published copy (if any),
     * allowing users to override default config values.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge package config with the application's config
    }

    /**
     * Bootstrap any package services.
     *
     * This method publishes the configuration file from the package
     * to the application's config directory, so the user can customize it.
     *
     * The publish group is tagged as 'config' so it can be published by:
     * php artisan vendor:publish --tag=config
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish the configuration file to the application's config directory
    }
}
