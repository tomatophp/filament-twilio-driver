<?php

namespace TomatoPHP\FilamentTwilioDriver\Services;

class TwilioWhatsappDriver extends TwilioDriver
{
    public function channel(): string
    {
        return Twilio::CHANNEL_WHATSAPP;
    }
}
