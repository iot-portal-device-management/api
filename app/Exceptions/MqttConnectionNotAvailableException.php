<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Exceptions;

use PhpMqtt\Client\Exceptions\MqttClientException;

/**
 * Class MqttConnectionNotAvailableException
 *
 */
class MqttConnectionNotAvailableException extends MqttClientException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('An MQTT connection with the name [%s] could not be found.', $name));
    }
}
