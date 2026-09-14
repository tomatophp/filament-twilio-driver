<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentTwilioDriver\FilamentTwilioDriverPlugin;

it('makes the plugin with its id', function () {
    expect(FilamentTwilioDriverPlugin::make())
        ->toBeInstanceOf(FilamentTwilioDriverPlugin::class)
        ->getId()->toBe('filament-twilio-driver');
});

it('registers the plugin on the panel', function () {
    expect(Filament::getPanel('admin')->getPlugin('filament-twilio-driver'))
        ->toBeInstanceOf(FilamentTwilioDriverPlugin::class);
});
