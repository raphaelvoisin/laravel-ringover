<?php
declare(strict_types=1);

namespace RaphaelVoisin\NotificationChannels\Ringover;

use Illuminate\Notifications\Notification;
use RaphaelVoisin\NotificationChannels\Ringover\Exceptions\CouldNotSendNotification;
use RaphaelVoisin\Ringover\Client;

class RingoverChannel
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string|null
     */
    private $defaultSenderPhone;

    public function __construct(
        Client $client,
        ?string $defaultSenderPhone = null
    ) {
        $this->client = $client;
        $this->defaultSenderPhone = $defaultSenderPhone;
    }

    /**
     * @param $notifiable
     *
     * @return \RaphaelVoisin\Ringover\Api\Push\Result\SendMessageResult
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toRingover($notifiable);

        if (\is_string($message)) {
            $message = new RingoverMessage($message);
        }

        if (!$message instanceof RingoverMessage) {
            throw new CouldNotSendNotification(sprintf('Message should be an instance of %s or a string', RingoverMessage::class));
        }

        $recipientPhone = $message->getRecipientPhone() ?? $notifiable->routeNotificationFor('ringover');

        if (!\is_string($recipientPhone)) {
            throw new CouldNotSendNotification('No recipient phone found');
        }

        $senderPhone = $message->getSenderPhone() ?? $this->defaultSenderPhone;

        if (!\is_string($senderPhone)) {
            throw new CouldNotSendNotification('No sender phone found');
        }

        return $this->client->pushApi->sendMessage($senderPhone, $recipientPhone, $message->getMessage());
    }
}
