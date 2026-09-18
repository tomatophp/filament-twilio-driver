![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-twilio-driver/master/arts/fadymondy-tomato-twilio-driver.jpg)

# Filament Twilio Driver

[![Dependabot Updates](https://github.com/tomatophp/filament-twilio-driver/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/tomatophp/filament-twilio-driver/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/tomatophp/filament-twilio-driver/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/tomatophp/filament-twilio-driver/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/tomatophp/filament-twilio-driver/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-twilio-driver/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-twilio-driver/version.svg)](https://packagist.org/packages/tomatophp/filament-twilio-driver)
[![License](https://poser.pugx.org/tomatophp/filament-twilio-driver/license.svg)](https://packagist.org/packages/tomatophp/filament-twilio-driver)
[![Downloads](https://poser.pugx.org/tomatophp/filament-twilio-driver/d/total.svg)](https://packagist.org/packages/tomatophp/filament-twilio-driver)

Twilio SMS and WhatsApp Notification Driver for [Filament Alerts Sender](https://github.com/tomatophp/filament-alerts)

## Screenshots

| Light | Dark |
|-------|------|
| ![Settings](https://raw.githubusercontent.com/tomatophp/filament-twilio-driver/master/arts/settings-light.png) | ![Settings](https://raw.githubusercontent.com/tomatophp/filament-twilio-driver/master/arts/settings-dark.png) |
| ![Settings Hub](https://raw.githubusercontent.com/tomatophp/filament-twilio-driver/master/arts/settings-hub-light.png) | ![Settings Hub](https://raw.githubusercontent.com/tomatophp/filament-twilio-driver/master/arts/settings-hub-dark.png) |
| ![Driver](https://raw.githubusercontent.com/tomatophp/filament-twilio-driver/master/arts/drivers-light.png) | ![Driver](https://raw.githubusercontent.com/tomatophp/filament-twilio-driver/master/arts/drivers-dark.png) |

## Requirements

| Package version | Filament | Laravel     | PHP  |
|-----------------|----------|-------------|------|
| 5.x             | 5.x      | 12.x, 13.x  | 8.2+ |

## Installation

```bash
composer require tomatophp/filament-twilio-driver
```
after install your package please run this command

```bash
php artisan filament-twilio-driver:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentTwilioDriver\FilamentTwilioDriverPlugin::make())
```

## Configuration

The package registers two Filament Alerts drivers:

| Driver key | Class | Sends |
|------------|-------|-------|
| `twilio-sms` | `TomatoPHP\FilamentTwilioDriver\Services\TwilioSmsDriver` | an SMS |
| `twilio-whatsapp` | `TomatoPHP\FilamentTwilioDriver\Services\TwilioWhatsappDriver` | a WhatsApp message |

Open **Settings Hub → Twilio Integration** and fill in the account SID, auth token, SMS sender
and WhatsApp sender, then switch the integration on. The auth token is a write only field: the
saved value is never rendered back into the page, and leaving it empty keeps the stored token.

Everything can also come from the environment, the settings hub simply wins when it is filled in:

```dotenv
TWILIO_DRIVER_ACTIVE=true
TWILIO_DRIVER_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_DRIVER_TOKEN=your-auth-token
TWILIO_DRIVER_FROM=+12025550123
TWILIO_DRIVER_WHATSAPP_FROM=+14155238886
TWILIO_DRIVER_PHONE_COLUMN=phone
```

| Setting key | Config key | Env |
|-------------|------------|-----|
| `twilio_active` | `filament-twilio-driver.active` | `TWILIO_DRIVER_ACTIVE` |
| `twilio_sid` | `filament-twilio-driver.sid` | `TWILIO_DRIVER_SID` |
| `twilio_token` | `filament-twilio-driver.token` | `TWILIO_DRIVER_TOKEN` |
| `twilio_from` | `filament-twilio-driver.from` | `TWILIO_DRIVER_FROM` |
| `twilio_whatsapp_from` | `filament-twilio-driver.whatsapp-from` | `TWILIO_DRIVER_WHATSAPP_FROM` |
| — | `filament-twilio-driver.phone-column` | `TWILIO_DRIVER_PHONE_COLUMN` |

Nothing is sent when the integration is off, when the credentials or the sender of the channel are
missing, or when the notifiable has no phone number.

## Usage

to set up any model to get notifications you

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use TomatoPHP\FilamentTwilioDriver\Traits\InteractsWithTwilio;

class User extends Authenticatable
{
    use Notifiable;
    use InteractsWithTwilio;
    ...
```

the phone number is read from the model's `phone` column, change
`filament-twilio-driver.phone-column` if your app stores it somewhere else.

### Queue

the notification is run on queue, so you must run the queue worker to send the notifications

```bash
php artisan queue:work
```

### Use Filament Native Notification

```php
use Filament\Notifications\Notification;

Notification::make('send')
    ->title('Test Notifications')
    ->body('This is a test notification')
    ->sendUse(auth()->user(), \TomatoPHP\FilamentTwilioDriver\Services\TwilioWhatsappDriver::class);
```

### Send Notification

```php
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;

FilamentAlerts::notify(User::first())
    ->template($template->id)
    ->title([
        "find-text" => "change with this"
    ])
    ->body([
        "find-text" => "change with this"
    ])
    ->drivers([\TomatoPHP\FilamentTwilioDriver\Services\TwilioSmsDriver::class])
    ->send();
```

### Notification Channels

it can be working with direct user methods like

```php
$user->notifyTwilioSms(string $title, ?string $message = null);
$user->notifyTwilioWhatsapp(string $title, ?string $message = null, ?string $image = null);
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-twilio-driver-config"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-twilio-driver-lang"
```

## Testing

if you like to run `PEST` testing just use this command

```bash
composer test
```

## Code Style

if you like to fix the code style just use this command

```bash
composer format
```

## PHPStan

if you like to check the code by `PHPStan` just use this command

```bash
composer analyse
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
