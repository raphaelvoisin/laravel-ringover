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
                $apiKey = config('services.ringover.api_key');

                return new Client($apiKey);
            });

        $ringoverChannel
            ->needs('$defaultSenderPhone')
            ->give(config('services.ringover.default_sender_phone'));
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
