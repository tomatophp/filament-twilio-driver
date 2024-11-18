![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-twilio-driver/master/art/3x1io-tomato-twilio-driver)

# Filament twilio driver

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-twilio-driver/version.svg)](https://packagist.org/packages/tomatophp/filament-twilio-driver)
[![License](https://poser.pugx.org/tomatophp/filament-twilio-driver/license.svg)](https://packagist.org/packages/tomatophp/filament-twilio-driver)
[![Downloads](https://poser.pugx.org/tomatophp/filament-twilio-driver/d/total.svg)](https://packagist.org/packages/tomatophp/filament-twilio-driver)

Twillio Whatsapp Integration For Filament Alerts Sender

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


## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-twilio-driver-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-twilio-driver-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-twilio-driver-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-twilio-driver-migrations"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
