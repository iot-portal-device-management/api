<?php

namespace App\Actions\Mqtt;

use App\Facades\MQTT;

class PublishMqttAction
{
    public function execute(string $topic, string $payload)
    {
        MQTT::publish($topic, $payload);
        MQTT::disconnect();
    }
}
