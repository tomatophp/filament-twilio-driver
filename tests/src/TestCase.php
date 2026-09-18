<?php

namespace TomatoPHP\FilamentTwilioDriver\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\SpatieLaravelSettingsPluginServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Spatie\LaravelSettings\LaravelSettingsServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentAlerts\FilamentAlertsServiceProvider;
use TomatoPHP\FilamentAlerts\Services\Concerns\NotificationDriver;
use TomatoPHP\FilamentIcons\FilamentIconsServiceProvider;
use TomatoPHP\FilamentSettingsHub\FilamentSettingsHubServiceProvider;
use TomatoPHP\FilamentTwilioDriver\FilamentTwilioDriverServiceProvider;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioSmsDriver;
use TomatoPHP\FilamentTwilioDriver\Services\TwilioWhatsappDriver;
use TomatoPHP\FilamentTwilioDriver\Tests\Fakes\FakeTwilioClient;
use TomatoPHP\FilamentTwilioDriver\Tests\Models\User;
use Twilio\Rest\Client;

#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    protected FakeTwilioClient $twilio;

    protected function setUp(): void
    {
        parent::setUp();

        // Nothing in the suite may reach Twilio, every test talks to this fake.
        $this->twilio = new FakeTwilioClient;
        $this->app->instance(Client::class, $this->twilio);

        FilamentAlerts::register([
            NotificationDriver::make('twilio-sms')
                ->label('Twilio SMS')
                ->driver(TwilioSmsDriver::class),
            NotificationDriver::make('twilio-whatsapp')
                ->label('Twilio WhatsApp')
                ->driver(TwilioWhatsappDriver::class),
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../vendor/tomatophp/filament-settings-hub/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function getPackageProviders($app): array
    {
        $providers = [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            LaravelSettingsServiceProvider::class,
            MediaLibraryServiceProvider::class,
            SpatieLaravelSettingsPluginServiceProvider::class,
            FilamentIconsServiceProvider::class,
            SchemasServiceProvider::class,
            FilamentSettingsHubServiceProvider::class,
            FilamentAlertsServiceProvider::class,
            FilamentTwilioDriverServiceProvider::class,
            AdminPanelProvider::class,
        ];

        sort($providers);

        return $providers;
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('auth.guards.testing.driver', 'session');
        $app['config']->set('auth.guards.testing.provider', 'testing');
        $app['config']->set('auth.providers.testing.driver', 'eloquent');
        $app['config']->set('auth.providers.testing.model', User::class);

        $app['config']->set('filament-translations.paths', [
            __DIR__ . '/../../vendor/orchestra/testbench-core/laravel',
        ]);

        $app['config']->set('filament-icons.cache', false);
        $app['config']->set('queue.default', 'sync');
        $app['config']->set('filament-alerts.try.model', User::class);

        $app['config']->set('filament-twilio-driver.active', true);
        $app['config']->set('filament-twilio-driver.sid', 'AC00000000000000000000000000000000');
        $app['config']->set('filament-twilio-driver.token', 'test-token');
        $app['config']->set('filament-twilio-driver.from', '+12025550123');
        $app['config']->set('filament-twilio-driver.whatsapp-from', '+14155238886');

        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__ . '/../resources/views',
        ]);
    }
}
