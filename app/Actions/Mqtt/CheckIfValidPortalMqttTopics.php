<?php

namespace App\Actions\Mqtt;

use App\Traits\CheckIfMqttClientIdPrefixMatch;

class CheckIfValidPortalMqttTopics
{
    use CheckIfMqttClientIdPrefixMatch;

    public function execute(string $username, string $clientId, string $topic): bool
    {
        $mqttConfig = config('mqtt_client.connections.' . config('mqtt_client.default_connection'));

        if (
            $username === $mqttConfig['connection_settings']['auth']['username']
            && $this->isMqttClientIdPrefixMatch($mqttConfig['client_id_prefix'], $clientId)
            && preg_match('/iotportal\/([\w\-]+)\/methods\/POST\/([\w\-_]+)\/\?\$rid=([\w\-]+)/', $topic)
        ) {
            return true;
        }

        return false;
    }
}
