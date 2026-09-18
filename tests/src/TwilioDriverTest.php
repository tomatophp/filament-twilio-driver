<?php

namespace TomatoPHP\FilamentTwilioDriver\Tests;

use TomatoPHP\FilamentTwilioDriver\Services\TwilioSmsDriver;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioWhatsappDriver;
use TomatoPHP\FilamentTwilioDriver\Tests\Models\User;

use function Pest\Laravel\assertDatabaseHas;

it('sends an SMS with the configured sender and the notifiable phone', function () {
    $user = User::factory()->create(['phone' => '+12025550999']);

    app(TwilioSmsDriver::class)->sendIt(
        title: 'Test title',
        model: User::class,
        modelId: $user->id,
        body: 'Test body',
    );

    expect($this->twilio->messages->sent)->toHaveCount(1);

    $message = $this->twilio->messages->sent[0];

    expect($message['to'])->toBe('+12025550999')
        ->and($message['options']['from'])->toBe('+12025550123')
        ->and($message['options']['body'])->toBe("Test title\nTest body")
        ->and($message['options'])->not->toHaveKey('mediaUrl');
});

it('sends a WhatsApp message on the whatsapp sender with the image attached', function () {
    $user = User::factory()->create(['phone' => '+12025550999']);

    app(TwilioWhatsappDriver::class)->sendIt(
        title: 'Test title',
        model: User::class,
        modelId: $user->id,
        body: 'Test body',
        image: 'https://tomatophp.com/logo.png',
    );

    $message = $this->twilio->messages->sent[0];

    expect($message['to'])->toBe('whatsapp:+12025550999')
        ->and($message['options']['from'])->toBe('whatsapp:+14155238886')
        ->and($message['options']['mediaUrl'])->toBe(['https://tomatophp.com/logo.png']);
});

it('logs the notification after sending', function () {
    $user = User::factory()->create(['phone' => '+12025550999']);

    app(TwilioSmsDriver::class)->sendIt(
        title: 'Logged title',
        model: User::class,
        modelId: $user->id,
        body: 'Logged body',
    );

    assertDatabaseHas('notifications_logs', [
        'title' => 'Logged title',
        'description' => 'Logged body',
        'provider' => 'twilio-sms',
        'type' => 'info',
    ]);
});

it('skips sending when the integration is disabled', function () {
    config()->set('filament-twilio-driver.active', false);

    $user = User::factory()->create(['phone' => '+12025550999']);

    app(TwilioSmsDriver::class)->sendIt(
        title: 'Test title',
        model: User::class,
        modelId: $user->id,
    );

    expect($this->twilio->messages->sent)->toBeEmpty();
});

it('skips sending when the credentials are missing', function () {
    config()->set('filament-twilio-driver.token', null);

    $user = User::factory()->create(['phone' => '+12025550999']);

    app(TwilioSmsDriver::class)->sendIt(
        title: 'Test title',
        model: User::class,
        modelId: $user->id,
    );

    expect($this->twilio->messages->sent)->toBeEmpty();
});

it('skips sending when the whatsapp sender is not configured', function () {
    config()->set('filament-twilio-driver.whatsapp-from', null);

    $user = User::factory()->create(['phone' => '+12025550999']);

    app(TwilioWhatsappDriver::class)->sendIt(
        title: 'Test title',
        model: User::class,
        modelId: $user->id,
    );

    expect($this->twilio->messages->sent)->toBeEmpty();
});

it('skips sending when the notifiable has no phone', function () {
    $user = User::factory()->create(['phone' => null]);

    app(TwilioSmsDriver::class)->sendIt(
        title: 'Test title',
        model: User::class,
        modelId: $user->id,
    );

    expect($this->twilio->messages->sent)->toBeEmpty();
});

it('skips sending when the notifiable does not exist', function () {
    app(TwilioSmsDriver::class)->sendIt(
        title: 'Test title',
        model: User::class,
        modelId: 9999,
    );

    expect($this->twilio->messages->sent)->toBeEmpty();
});

it('sends to the phone column configured for the application', function () {
    config()->set('filament-twilio-driver.phone-column', 'mobile');

    $user = User::factory()->create(['phone' => '+12025550999', 'mobile' => '+12025550777']);

    app(TwilioSmsDriver::class)->sendIt(
        title: 'Test title',
        model: User::class,
        modelId: $user->id,
    );

    expect($this->twilio->messages->sent[0]['to'])->toBe('+12025550777');
});
