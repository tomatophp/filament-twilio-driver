<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentTwilioDriver\Filament\Pages\TwilioSettingsPage;
use TomatoPHP\FilamentTwilioDriver\FilamentTwilioDriverPlugin;
use TomatoPHP\FilamentTwilioDriver\Services\Twilio;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioSmsDriver;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioWhatsappDriver;

it('registers plugin', function () {
    $panel = Filament::getCurrentOrDefaultPanel();

    $panel->plugins([
        FilamentTwilioDriverPlugin::make(),
    ]);

    expect($panel->getPlugin('filament-twilio-driver'))
        ->not()
        ->toThrow(Exception::class);
});

it('registers the settings page on the panel', function () {
    expect(Filament::getCurrentOrDefaultPanel()->getPages())
        ->toContain(TwilioSettingsPage::class);
});

it('registers both twilio drivers with filament alerts', function () {
    $drivers = FilamentAlerts::loadDrivers()->pluck('driver')->toArray();

    expect($drivers)->toContain(TwilioSmsDriver::class)
        ->and($drivers)->toContain(TwilioWhatsappDriver::class);
});

it('formats numbers for the channel it sends on', function () {
    expect(Twilio::address('+12025550123', Twilio::CHANNEL_SMS))->toBe('+12025550123')
        ->and(Twilio::address('whatsapp:+12025550123', Twilio::CHANNEL_SMS))->toBe('+12025550123')
        ->and(Twilio::address('+12025550123', Twilio::CHANNEL_WHATSAPP))->toBe('whatsapp:+12025550123')
        ->and(Twilio::address('whatsapp:+12025550123', Twilio::CHANNEL_WHATSAPP))->toBe('whatsapp:+12025550123');
});
