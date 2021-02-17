<?php
declare(strict_types=1);

namespace RaphaelVoisin\NotificationChannels\Ringover;

use Illuminate\Support\ServiceProvider;
use RaphaelVoisin\Ringover\Client;

class RingoverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $ringoverChannel = $this->app->when(RingoverChannel::class);

        $ringoverChannel->needs(Client::class)
            ->give(function () {
                $apiKey = config('services.ringover.default_api_key');

                return new Client($apiKey);
            });

        $ringoverChannel
            ->needs('$enabled')
            ->give((bool)config('services.ringover.enabled', true));

        $ringoverChannel
            ->needs('$defaultSenderPhone')
            ->give(config('services.ringover.default_sender_phone'));

        $ringoverChannel
            ->needs('$overriddenRecipientPhone')
            ->give(config('services.ringover.overridden_recipient_phone'));
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
