<?php

namespace TomatoPHP\FilamentTwilioDriver\Settings;

use Spatie\LaravelSettings\Settings;

class TwilioSettings extends Settings
{
    public ?bool $twilio_active = false;

    public ?string $twilio_sid = null;

    public ?string $twilio_token = null;

    public ?string $twilio_from = null;

    public ?string $twilio_whatsapp_from = null;

    public static function group(): string
    {
        return 'twilio';
    }
}
