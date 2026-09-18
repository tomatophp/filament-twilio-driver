<?php

namespace TomatoPHP\FilamentTwilioDriver\Services;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentAlerts\Services\Drivers\Driver;
use TomatoPHP\FilamentTwilioDriver\Jobs\NotifyTwilioJob;

abstract class TwilioDriver extends Driver
{
    /**
     * Which Twilio channel the driver sends through.
     */
    abstract public function channel(): string;

    public function setup(): void
    {
        // TODO: Implement setup() method.
    }

    public function sendIt(
        string $title,
        string $model,
        int | string | null $modelId = null,
        ?string $body = null,
        ?string $url = null,
        ?string $icon = null,
        ?string $image = null,
        ?string $type = 'info',
        ?string $action = 'system',
        ?array $data = [],
        ?int $template_id = null,
        ?Notification $notification = null
    ): void {
        $phone = $this->phoneFor($model, $modelId);

        // No phone number on the notifiable: there is nothing Twilio could deliver to.
        if (blank($phone)) {
            return;
        }

        dispatch(new NotifyTwilioJob([
            'phone' => $phone,
            'title' => $title,
            'message' => $body,
            'image' => $image,
            'channel' => $this->channel(),
            'model' => $model,
            'modelId' => $modelId,
        ]))->onQueue(config('filament-alerts.queue'));
    }

    /**
     * Read the phone number from the notifiable record.
     */
    protected function phoneFor(string $model, int | string | null $modelId): ?string
    {
        if (blank($modelId) || ! class_exists($model) || ! is_subclass_of($model, Model::class)) {
            return null;
        }

        $record = $model::query()->find($modelId);

        $column = (string) config('filament-twilio-driver.phone-column', 'phone');

        $phone = $record?->getAttribute($column);

        return filled($phone) ? (string) $phone : null;
    }
}
