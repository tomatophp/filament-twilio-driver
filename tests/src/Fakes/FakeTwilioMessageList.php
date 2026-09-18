<?php

namespace TomatoPHP\FilamentTwilioDriver\Tests\Fakes;

class FakeTwilioMessageList
{
    /**
     * @var array<int, array{to: string, options: array<string, mixed>}>
     */
    public array $sent = [];

    /**
     * @param  array<string, mixed>  $options
     */
    public function create(string $to, array $options): FakeTwilioMessage
    {
        $this->sent[] = ['to' => $to, 'options' => $options];

        return new FakeTwilioMessage('SM' . str_pad((string) count($this->sent), 32, '0', STR_PAD_LEFT));
    }
}
