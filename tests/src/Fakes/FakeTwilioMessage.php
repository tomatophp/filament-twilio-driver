<?php

namespace TomatoPHP\FilamentTwilioDriver\Tests\Fakes;

class FakeTwilioMessage
{
    public function __construct(public string $sid) {}
}
