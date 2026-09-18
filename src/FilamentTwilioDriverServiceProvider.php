<?php

namespace TomatoPHP\FilamentTwilioDriver;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentTwilioDriver\Console\FilamentTwilioDriverInstall;
use Twilio\Rest\Client;

class FilamentTwilioDriverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register generate command
        $this->commands([
            FilamentTwilioDriverInstall::class,
        ]);

        // Register Config file
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-twilio-driver.php', 'filament-twilio-driver');

        // Publish Config
        $this->publishes([
            __DIR__ . '/../config/filament-twilio-driver.php' => config_path('filament-twilio-driver.php'),
        ], 'filament-twilio-driver-config');

        // Register Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'filament-twilio-driver-migrations');
        // Register views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-twilio-driver');

        // Publish Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-twilio-driver'),
        ], 'filament-twilio-driver-views');

        // Register Langs
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-twilio-driver');

        // Publish Lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('lang/vendor/filament-twilio-driver'),
        ], 'filament-twilio-driver-lang');

        // Register Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Resolved lazily so tests can swap in a fake client and nothing ever hits the network.
        $this->app->bind(Client::class, fn (): Client => new Client(
            config('filament-twilio-driver.sid'),
            config('filament-twilio-driver.token'),
        ));
    }

    public function boot(): void
    {
        try {
            // Settings saved from the settings hub win over the env based config, empty settings keep the config value.
            foreach ([
                'active' => 'twilio_active',
                'sid' => 'twilio_sid',
                'token' => 'twilio_token',
                'from' => 'twilio_from',
                'whatsapp-from' => 'twilio_whatsapp_from',
            ] as $config => $setting) {
                $value = setting($setting);

                if (filled($value)) {
                    Config::set("filament-twilio-driver.{$config}", $value);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}
