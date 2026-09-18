<?php

namespace TomatoPHP\FilamentTwilioDriver\Services;

class TwilioSmsDriver extends TwilioDriver
{
    public function channel(): string
    {
        return Twilio::CHANNEL_SMS;
    }
}
