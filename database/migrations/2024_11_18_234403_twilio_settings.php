<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('twilio.twilio_active', false);
        $this->migrator->add('twilio.twilio_sid', '');
        $this->migrator->add('twilio.twilio_token', '');
        $this->migrator->add('twilio.twilio_from', '');
        $this->migrator->add('twilio.twilio_whatsapp_from', '');
    }
};
