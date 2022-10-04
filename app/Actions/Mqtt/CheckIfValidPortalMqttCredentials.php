<?php

namespace App\Actions\Mqtt;

class CheckIfValidPortalMqttCredentials
{
    public function execute(string $username, string $password, string $clientId): bool
    {
        $mqttConfig = config('mqtt_client.connections.' . config('mqtt_client.default_connection'));

        if (
            $username === $mqttConfig['auth']['username']
            && $password === $mqttConfig['auth']['password']
            && $clientId === $mqttConfig['client_id']
        ) {
            return true;
        }

        return false;
    }
}
