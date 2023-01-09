<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\Mqtt;

use App\Facades\MQTT;

class PublishMqttAction
{
    public function execute(string $topic, string $payload): void
    {
        $defaultConnection = config('mqtt_client.default_connection', 'default');
        $mqttConfig = config('mqtt_client.connections.' . $defaultConnection);

        MQTT::connection($defaultConnection, $mqttConfig['client_id_prefix']);
        MQTT::publish($topic, $payload);
        MQTT::disconnect();
    }
}
