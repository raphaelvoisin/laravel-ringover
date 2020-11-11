<?php
declare(strict_types=1);

namespace RaphaelVoisin\NotificationChannels\Ringover;

class RingoverMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string|null
     */
    private $recipientPhone;

    /**
     * @var string|null
     */
    private $senderPhone;

    /**
     * @param ?string $senderPhone phone in e164 format
     * @param ?string $recipientPhone phone in e164 format
     */
    public function __construct(
        string $message,
        ?string $recipientPhone = null,
        ?string $senderPhone = null
    ) {
        $this->message = $message;
        $this->recipientPhone = $recipientPhone;
        $this->senderPhone = $senderPhone;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return RingoverMessage
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getRecipientPhone(): ?string
    {
        return $this->recipientPhone;
    }

    public function setRecipientPhone(?string $recipientPhone): self
    {
        $this->recipientPhone = $recipientPhone;

        return $this;
    }

    public function getSenderPhone(): ?string
    {
        return $this->senderPhone;
    }

    public function setSenderPhone(?string $senderPhone): self
    {
        $this->senderPhone = $senderPhone;

        return $this;
    }
}
