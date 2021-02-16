<?php
declare(strict_types=1);

namespace RaphaelVoisin\NotificationChannels\Ringover;

interface ApiKeyResolver
{
    public function resolve(string $phoneNumber): ?string;
}
