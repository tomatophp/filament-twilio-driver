<?php

namespace TomatoPHP\FilamentTwilioDriver\Tests;

use Illuminate\Support\Facades\DB;
use TomatoPHP\FilamentTwilioDriver\FilamentTwilioDriverServiceProvider;

function saveTwilioSetting(string $name, mixed $value): void
{
    DB::table('settings')->updateOrInsert(
        ['group' => 'twilio', 'name' => $name],
        ['payload' => json_encode($value), 'locked' => false],
    );
}

function bootTwilioProvider(): void
{
    (new FilamentTwilioDriverServiceProvider(app()))->boot();
}

it('loads the credentials saved from the settings hub', function () {
    saveTwilioSetting('twilio_sid', 'AC33333333333333333333333333333333');
    saveTwilioSetting('twilio_token', 'hub-token');
    saveTwilioSetting('twilio_from', '+12025550444');
    saveTwilioSetting('twilio_whatsapp_from', '+14155238444');
    saveTwilioSetting('twilio_active', true);

    bootTwilioProvider();

    expect(config('filament-twilio-driver.sid'))->toBe('AC33333333333333333333333333333333')
        ->and(config('filament-twilio-driver.token'))->toBe('hub-token')
        ->and(config('filament-twilio-driver.from'))->toBe('+12025550444')
        ->and(config('filament-twilio-driver.whatsapp-from'))->toBe('+14155238444')
        ->and(config('filament-twilio-driver.active'))->toBeTrue();
});

it('keeps the env credentials when the settings are empty', function () {
    config()->set('filament-twilio-driver.sid', 'AC-from-env');
    saveTwilioSetting('twilio_sid', '');

    bootTwilioProvider();

    expect(config('filament-twilio-driver.sid'))->toBe('AC-from-env');
});

it('turns the driver off when the hub toggle is off', function () {
    saveTwilioSetting('twilio_active', false);

    bootTwilioProvider();

    expect(config('filament-twilio-driver.active'))->toBeFalse();
});
