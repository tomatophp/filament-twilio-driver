<?php

namespace TomatoPHP\FilamentTwilioDriver\Tests;

use TomatoPHP\FilamentTwilioDriver\Filament\Pages\TwilioSettingsPage;
use TomatoPHP\FilamentTwilioDriver\Settings\TwilioSettings;
use TomatoPHP\FilamentTwilioDriver\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can render Twilio Settings Page', function () {
    get(TwilioSettingsPage::getUrl())->assertSuccessful();
});

it('saves the settings', function () {
    livewire(TwilioSettingsPage::class)
        ->fillForm([
            'twilio_active' => true,
            'twilio_sid' => 'AC11111111111111111111111111111111',
            'twilio_token' => 'brand-new-token',
            'twilio_from' => '+12025550111',
            'twilio_whatsapp_from' => '+14155238880',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(TwilioSettings::class);

    expect($settings->twilio_active)->toBeTrue()
        ->and($settings->twilio_sid)->toBe('AC11111111111111111111111111111111')
        ->and($settings->twilio_token)->toBe('brand-new-token')
        ->and($settings->twilio_from)->toBe('+12025550111')
        ->and($settings->twilio_whatsapp_from)->toBe('+14155238880');
});

it('keeps the stored auth token when the field is left empty', function () {
    $settings = app(TwilioSettings::class);
    $settings->twilio_token = 'stored-secret-token';
    $settings->save();

    livewire(TwilioSettingsPage::class)
        ->fillForm([
            'twilio_active' => true,
            'twilio_sid' => 'AC22222222222222222222222222222222',
            'twilio_token' => null,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(app(TwilioSettings::class)->twilio_token)->toBe('stored-secret-token');
});

it('never sends the stored auth token back to the browser', function () {
    $settings = app(TwilioSettings::class);
    $settings->twilio_active = true;
    $settings->twilio_token = 'stored-secret-token';
    $settings->save();

    $page = livewire(TwilioSettingsPage::class);

    expect($page->get('data.twilio_token'))->toBeNull();

    $page->assertDontSee('stored-secret-token');

    get(TwilioSettingsPage::getUrl())
        ->assertSuccessful()
        ->assertDontSee('stored-secret-token');
});
