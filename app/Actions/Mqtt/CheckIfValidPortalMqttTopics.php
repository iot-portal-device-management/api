<?php

namespace App\Actions\Mqtt;

class CheckIfValidPortalMqttTopics
{
    public function execute(string $username, string $clientId, string $topic): bool
    {
        $mqttConfig = config('mqtt_client.connections.' . config('mqtt_client.default_connection'));

        if (
            $username === $mqttConfig['auth']['username']
            && $clientId === $mqttConfig['auth']['client_id']
            && preg_match('/iotportal\/([\w\-]+)\/methods\/POST\/([\w\-_]+)\/\?\$rid=([\d]+)/', $topic)
        ) {
            return true;
        }

        return false;
    }
}
