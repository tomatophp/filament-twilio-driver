<?php

use function Pest\Laravel\artisan;

it('runs the install command', function () {
    artisan('filament-twilio-driver:install')->assertSuccessful();
});
