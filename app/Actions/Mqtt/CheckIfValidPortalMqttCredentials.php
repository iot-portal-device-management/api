<?php

namespace App\Actions\Mqtt;

class CheckIfValidPortalMqttCredentials
{
    public function execute(string $username, string $password, string $clientId): bool
    {
        $mqttConfig = config('mqtt_client.connections.' . config('mqtt_client.default_connection'));

        if (
            $username === $mqttConfig['connection_settings']['auth']['username']
            && $password === $mqttConfig['connection_settings']['auth']['password']
            && $clientId === $mqttConfig['client_id']
        ) {
            return true;
        }

        return false;
    }
}
