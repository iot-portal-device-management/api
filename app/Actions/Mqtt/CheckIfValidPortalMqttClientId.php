<?php

namespace App\Actions\Mqtt;

class CheckIfValidPortalMqttClientId
{
    public function execute(string $clientId): bool
    {
        $mqttConfig = config('mqtt_client.connections.' . config('mqtt_client.default_connection'));

        return $clientId === $mqttConfig['client_id'];
    }
}
