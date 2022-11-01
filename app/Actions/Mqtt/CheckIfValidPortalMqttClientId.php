<?php

namespace App\Actions\Mqtt;

use App\Traits\CheckIfMqttClientIdPrefixMatch;

class CheckIfValidPortalMqttClientId
{
    use CheckIfMqttClientIdPrefixMatch;

    public function execute(string $clientId): bool
    {
        $mqttConfig = config('mqtt_client.connections.' . config('mqtt_client.default_connection'));

        return $this->isMqttClientIdPrefixMatch($mqttConfig['client_id_prefix'], $clientId);
    }
}
