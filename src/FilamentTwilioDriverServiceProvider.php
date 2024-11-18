<?php

namespace TomatoPHP\FilamentTwilioDriver;

use Illuminate\Support\ServiceProvider;

class FilamentTwilioDriverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
            \TomatoPHP\FilamentTwilioDriver\Console\FilamentTwilioDriverInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-twilio-driver.php', 'filament-twilio-driver');

        //Publish Config
        $this->publishes([
            __DIR__ . '/../config/filament-twilio-driver.php' => config_path('filament-twilio-driver.php'),
        ], 'filament-twilio-driver-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        //Publish Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'filament-twilio-driver-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-twilio-driver');

        //Publish Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-twilio-driver'),
        ], 'filament-twilio-driver-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-twilio-driver');

        //Publish Lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('lang/vendor/filament-twilio-driver'),
        ], 'filament-twilio-driver-lang');

        //Register Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

    }

    public function boot(): void
    {
        //you boot methods here
    }
}
