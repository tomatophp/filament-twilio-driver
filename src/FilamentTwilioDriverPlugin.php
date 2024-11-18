<?php

namespace TomatoPHP\FilamentTwilioDriver;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentTwilioDriverPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-twilio-driver';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): self
    {
        return new FilamentTwilioDriverPlugin;
    }
}
