<?php


namespace RaphaelVoisin\NotificationChannels\Ringover;


interface ApiKeyResolver
{
    public function resolve(string $phoneNumber): ?string;
}
