<?php

namespace TomatoPHP\FilamentTwilioDriver\Traits;

use TomatoPHP\FilamentTwilioDriver\Jobs\NotifyTwilioJob;
use TomatoPHP\FilamentTwilioDriver\Services\Twilio;

trait InteractsWithTwilio
{
    /**
     * Send an SMS to the model's phone column through Twilio.
     */
    public function notifyTwilioSms(string $title, ?string $message = null): void
    {
        $this->notifyTwilio(Twilio::CHANNEL_SMS, $title, $message);
    }

    /**
     * Send a WhatsApp message to the model's phone column through Twilio.
     */
    public function notifyTwilioWhatsapp(string $title, ?string $message = null, ?string $image = null): void
    {
        $this->notifyTwilio(Twilio::CHANNEL_WHATSAPP, $title, $message, $image);
    }

    protected function notifyTwilio(string $channel, string $title, ?string $message = null, ?string $image = null): void
    {
        dispatch(new NotifyTwilioJob([
            'phone' => $this->getAttribute((string) config('filament-twilio-driver.phone-column', 'phone')),
            'title' => $title,
            'message' => $message,
            'image' => $image,
            'channel' => $channel,
            'model' => static::class,
            'modelId' => $this->getKey(),
        ]));
    }
}
