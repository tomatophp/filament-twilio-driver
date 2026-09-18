<?php

namespace TomatoPHP\FilamentTwilioDriver\Tests\Fakes;

/**
 * A stand in for `Twilio\Rest\Client` so the suite never opens a socket.
 */
class FakeTwilioClient
{
    public FakeTwilioMessageList $messages;

    public function __construct()
    {
        $this->messages = new FakeTwilioMessageList;
    }
}
