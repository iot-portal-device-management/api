<?php

namespace App\Traits;

trait CheckIfMqttClientIdPrefixMatch
{
    protected function isMqttClientIdPrefixMatch(string $clientIdPrefix, string $clientId): bool
    {
        return str_starts_with($clientId, $clientIdPrefix);
    }
}
