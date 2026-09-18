# v5.1.0

- first working release: `twilio-sms` and `twilio-whatsapp` drivers for `tomatophp/filament-alerts` ^5.0
- Twilio settings page registered on the settings hub, with a write only auth token
- the Twilio client is resolved from the container, so it can be swapped in tests
- nothing is sent when the integration is off, unconfigured, or the notifiable has no phone
- support Filament v5 and Laravel 12 / 13
- Pest 4 / Testbench 10–11 test suite and GitHub Actions matrix (PHP 8.3–8.4, Laravel 12–13)

# V1.0.0

First release of the package
