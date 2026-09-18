<?php

namespace TomatoPHP\FilamentTwilioDriver\Tests;

use Filament\Notifications\Notification;
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioSmsDriver;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioWhatsappDriver;
use TomatoPHP\FilamentTwilioDriver\Tests\Models\NotificationsTemplate;
use TomatoPHP\FilamentTwilioDriver\Tests\Models\User;

use function Pest\Laravel\assertDatabaseHas;

it('can use FilamentAlerts Facade To Notify User By SMS', function () {
    $user = User::factory()->create(['phone' => '+12025550999']);
    $template = NotificationsTemplate::factory()->create();

    FilamentAlerts::notify($user)
        ->template($template->id)
        ->drivers([TwilioSmsDriver::class])
        ->title(['name' => $user->name])
        ->body(['date' => now()->toDateTimeString()])
        ->send();

    expect($this->twilio->messages->sent)->toHaveCount(1);

    assertDatabaseHas('notifications_logs', [
        'title' => $template->title,
        'description' => $template->body,
        'provider' => 'twilio-sms',
        'type' => 'info',
    ]);
});

it('can send notification using Filament Native Notification', function () {
    $user = User::factory()->create(['phone' => '+12025550999']);

    Notification::make()
        ->title('Test title')
        ->body('Test body')
        ->icon('heroicon-o-bell')
        ->info()
        ->sendUse($user, TwilioWhatsappDriver::class);

    expect($this->twilio->messages->sent[0]['to'])->toBe('whatsapp:+12025550999');

    assertDatabaseHas('notifications_logs', [
        'title' => 'Test title',
        'description' => 'Test body',
        'provider' => 'twilio-whatsapp',
        'type' => 'info',
    ]);
});

it('can notify the model directly through the trait', function () {
    $user = User::factory()->create(['phone' => '+12025550999']);

    $user->notifyTwilioSms('Direct title', 'Direct body');

    expect($this->twilio->messages->sent[0]['options']['body'])->toBe("Direct title\nDirect body");

    $user->notifyTwilioWhatsapp('WhatsApp title');

    expect($this->twilio->messages->sent[1]['to'])->toBe('whatsapp:+12025550999');
});
