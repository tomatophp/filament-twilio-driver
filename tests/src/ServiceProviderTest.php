<?php

use Illuminate\Support\Facades\Artisan;
use TomatoPHP\FilamentTwilioDriver\FilamentTwilioDriverServiceProvider;

it('boots the service provider', function () {
    expect(app()->getProviders(FilamentTwilioDriverServiceProvider::class))->not->toBeEmpty();
});

it('merges the package config', function () {
    expect(config()->has('filament-twilio-driver'))->toBeTrue()
        ->and(config('filament-twilio-driver'))->toBeArray();
});

it('registers the install command', function () {
    expect(Artisan::all())->toHaveKey('filament-twilio-driver:install');
});
