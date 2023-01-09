<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\Mqtt;

use App\Traits\CheckIfMqttClientIdPrefixMatch;

class CheckIfValidPortalMqttCredentials
{
    use CheckIfMqttClientIdPrefixMatch;

    public function execute(string $username, string $password, string $clientId): bool
    {
        $mqttConfig = config('mqtt_client.connections.' . config('mqtt_client.default_connection'));

        if (
            $username === $mqttConfig['connection_settings']['auth']['username']
            && $password === $mqttConfig['connection_settings']['auth']['password']
            && $this->isMqttClientIdPrefixMatch($mqttConfig['client_id_prefix'], $clientId)
        ) {
            return true;
        }

        return false;
    }
}
