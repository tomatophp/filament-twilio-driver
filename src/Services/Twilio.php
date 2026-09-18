<?php

namespace TomatoPHP\FilamentTwilioDriver\Services;

use Twilio\Rest\Client;

class Twilio
{
    public const CHANNEL_SMS = 'sms';

    public const CHANNEL_WHATSAPP = 'whatsapp';

    /**
     * Whether the account credentials and the sender of the given channel are all filled in.
     */
    public static function isConfigured(string $channel = self::CHANNEL_SMS): bool
    {
        if (! config('filament-twilio-driver.active')) {
            return false;
        }

        return filled(config('filament-twilio-driver.sid'))
            && filled(config('filament-twilio-driver.token'))
            && filled(static::sender($channel));
    }

    /**
     * The number messages of the given channel are sent from.
     */
    public static function sender(string $channel): ?string
    {
        $sender = $channel === self::CHANNEL_WHATSAPP
            ? config('filament-twilio-driver.whatsapp-from')
            : config('filament-twilio-driver.from');

        return filled($sender) ? (string) $sender : null;
    }

    /**
     * Send a message through Twilio and return the created message SID.
     *
     * The client is resolved from the container so a fake can be bound in tests.
     */
    public static function send(string $phone, string $message, string $channel = self::CHANNEL_SMS, ?string $mediaUrl = null): string
    {
        /** @var Client $client */
        $client = app(Client::class);

        $body = ['body' => $message];

        if (filled($mediaUrl)) {
            $body['mediaUrl'] = [$mediaUrl];
        }

        $body['from'] = static::address((string) static::sender($channel), $channel);

        return $client->messages->create(static::address($phone, $channel), $body)->sid;
    }

    /**
     * WhatsApp numbers must be prefixed with the `whatsapp:` scheme, SMS numbers must not.
     */
    public static function address(string $phone, string $channel): string
    {
        if ($channel !== self::CHANNEL_WHATSAPP) {
            return str($phone)->after('whatsapp:')->trim()->toString();
        }

        return str($phone)->trim()->start('whatsapp:')->toString();
    }
}
