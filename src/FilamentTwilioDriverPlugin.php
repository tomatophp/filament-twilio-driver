<?php

namespace TomatoPHP\FilamentTwilioDriver;

use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentAlerts\Services\Concerns\NotificationDriver;
use TomatoPHP\FilamentSettingsHub\Facades\FilamentSettingsHub;
use TomatoPHP\FilamentSettingsHub\Services\Contracts\SettingHold;
use TomatoPHP\FilamentTwilioDriver\Filament\Pages\TwilioSettingsPage;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioSmsDriver;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioWhatsappDriver;

class FilamentTwilioDriverPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-twilio-driver';
    }

    public function register(Panel $panel): void
    {
        if (class_exists(FilamentSettingsHub::class) && $panel->getPlugin('filament-alerts')->useSettingsHub) {
            $panel->pages([
                TwilioSettingsPage::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        if (class_exists(FilamentSettingsHub::class) && filament('filament-alerts')->useSettingsHub) {
            FilamentSettingsHub::register([
                SettingHold::make()
                    ->label('filament-twilio-driver::messages.settings.twilio.title')
                    ->icon('bxl-whatsapp')
                    ->page(TwilioSettingsPage::class)
                    ->order(2)
                    ->description('filament-twilio-driver::messages.settings.twilio.description')
                    ->group('filament-alerts::messages.settings.group'),
            ]);
        }

        FilamentAlerts::register([
            NotificationDriver::make('twilio-sms')
                ->label('Twilio SMS')
                ->driver(TwilioSmsDriver::class),
            NotificationDriver::make('twilio-whatsapp')
                ->label('Twilio WhatsApp')
                ->driver(TwilioWhatsappDriver::class),
        ]);
    }

    public static function make(): self
    {
        return new FilamentTwilioDriverPlugin;
    }
}
