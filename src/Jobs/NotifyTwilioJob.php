<?php

namespace TomatoPHP\FilamentTwilioDriver\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TomatoPHP\FilamentAlerts\Models\NotificationsLogs;
use TomatoPHP\FilamentTwilioDriver\Services\Twilio;

class NotifyTwilioJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public ?string $phone;

    public ?string $title;

    public ?string $message;

    public ?string $image;

    public string $channel;

    public ?string $model;

    public int | string | null $modelId;

    /**
     * @param  array{phone: ?string, title: ?string, message?: ?string, image?: ?string, channel?: string, model?: ?string, modelId?: int|string|null}  $arg
     */
    public function __construct(array $arg)
    {
        $this->phone = $arg['phone'] ?? null;
        $this->title = $arg['title'] ?? null;
        $this->message = $arg['message'] ?? null;
        $this->image = $arg['image'] ?? null;
        $this->channel = $arg['channel'] ?? Twilio::CHANNEL_SMS;
        $this->model = $arg['model'] ?? null;
        $this->modelId = $arg['modelId'] ?? null;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Nothing to send to, or the integration is off / half filled in: stay silent.
        if (blank($this->phone) || ! Twilio::isConfigured($this->channel)) {
            return;
        }

        $body = collect([$this->title, $this->message])->filter()->implode("\n");

        if (blank($body)) {
            return;
        }

        Twilio::send(
            phone: $this->phone,
            message: $body,
            channel: $this->channel,
            mediaUrl: $this->channel === Twilio::CHANNEL_WHATSAPP ? $this->image : null,
        );

        $log = new NotificationsLogs;
        $log->model_type = $this->model;
        $log->model_id = $this->modelId;
        $log->title = $this->title;
        $log->description = $this->message;
        $log->provider = 'twilio-' . $this->channel;
        $log->type = 'info';
        $log->save();
    }
}
