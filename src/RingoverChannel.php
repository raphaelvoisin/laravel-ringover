<?php
declare(strict_types=1);

namespace RaphaelVoisin\NotificationChannels\Ringover;

use Illuminate\Notifications\Notification;
use RaphaelVoisin\NotificationChannels\Ringover\Exceptions\CouldNotSendNotification;
use RaphaelVoisin\Ringover\Client;
use RaphaelVoisin\Ringover\Exception\RingoverApiException;

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

    /**
     * @var string|null
     */
    private $overriddenRecipientPhone;
    /**
     * @var bool|bool
     */
    private $enabled;

    public function __construct(
        Client $client,
        bool $enabled = true,
        ?string $defaultSenderPhone = null,
        ?string $overriddenRecipientPhone = null
    )
    {
        $this->client = $client;
        $this->enabled = $enabled;
        $this->defaultSenderPhone = $defaultSenderPhone;
        $this->overriddenRecipientPhone = $overriddenRecipientPhone;
    }

    /**
     * @param $notifiable
     *
     * @return \RaphaelVoisin\Ringover\Api\Push\Result\SendMessageResult|null
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

        if (\is_string($this->overriddenRecipientPhone)) {
            $recipientPhone = $this->overriddenRecipientPhone;
        }

        $senderPhone = $message->getSenderPhone() ?? $this->defaultSenderPhone;

        if (!\is_string($senderPhone)) {
            throw new CouldNotSendNotification('No sender phone found');
        }

        if (!$this->enabled) {
            return null;
        }

        try {
            return $this->client->pushApi->sendMessage($senderPhone, $recipientPhone, $message->getMessage());
        } catch (RingoverApiException $e) {
            throw new CouldNotSendNotification('Error from Ringover API: ' . $e->getMessage());
        }
    }
}
