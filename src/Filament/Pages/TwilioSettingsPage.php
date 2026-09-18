<?php

namespace TomatoPHP\FilamentTwilioDriver\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Artisan;
use TomatoPHP\FilamentSettingsHub\Pages\SettingsHub;
use TomatoPHP\FilamentTwilioDriver\Settings\TwilioSettings;

class TwilioSettingsPage extends SettingsPage
{
    protected static BackedEnum | null | string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = TwilioSettings::class;

    /**
     * Settings that hold a secret and must never be sent back to the browser.
     *
     * @var array<int, string>
     */
    protected const SECRET_SETTINGS = [
        'twilio_token',
    ];

    public function getTitle(): string
    {
        return trans('filament-twilio-driver::messages.settings.twilio.title');
    }

    protected function getActions(): array
    {
        return [
            Action::make('back')->url(SettingsHub::getUrl()),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function form(Schema $form): Schema
    {
        return $form->columns(1)
            ->schema([
                Section::make()->schema([
                    Toggle::make('twilio_active')
                        ->live()
                        ->label(trans('filament-twilio-driver::messages.settings.twilio.active'))
                        ->hint(config('filament-alerts.show_hint') ? 'setting("twilio_active")' : null),
                ]),
                Section::make(trans('filament-twilio-driver::messages.settings.twilio.account'))
                    ->visible(fn (Get $get): bool => (bool) $get('twilio_active'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('twilio_sid')
                            ->label(trans('filament-twilio-driver::messages.settings.twilio.sid'))
                            ->hint(config('filament-alerts.show_hint') ? 'setting("twilio_sid")' : null),
                        // Write only: the stored token is never rendered, an empty value keeps it.
                        TextInput::make('twilio_token')
                            ->password()
                            ->revealable(false)
                            ->autocomplete('new-password')
                            ->label(trans('filament-twilio-driver::messages.settings.twilio.token'))
                            ->hint(trans('filament-twilio-driver::messages.settings.twilio.keep_secret')),
                        TextInput::make('twilio_from')
                            ->tel()
                            ->label(trans('filament-twilio-driver::messages.settings.twilio.from'))
                            ->helperText(trans('filament-twilio-driver::messages.settings.twilio.from_help'))
                            ->hint(config('filament-alerts.show_hint') ? 'setting("twilio_from")' : null),
                        TextInput::make('twilio_whatsapp_from')
                            ->tel()
                            ->label(trans('filament-twilio-driver::messages.settings.twilio.whatsapp_from'))
                            ->helperText(trans('filament-twilio-driver::messages.settings.twilio.whatsapp_from_help'))
                            ->hint(config('filament-alerts.show_hint') ? 'setting("twilio_whatsapp_from")' : null),
                    ]),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach (static::SECRET_SETTINGS as $secret) {
            $data[$secret] = null;
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $settings = app(static::getSettings());

        foreach (static::SECRET_SETTINGS as $secret) {
            if (blank($data[$secret] ?? null)) {
                $data[$secret] = $settings->{$secret};
            }
        }

        return $data;
    }

    public function afterSave(): void
    {
        Artisan::call('cache:clear');
    }
}
