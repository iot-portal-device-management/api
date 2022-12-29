<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\Mqtt;

class PublishMqttToDeviceAction
{
    private PublishMqttAction $publishMqttAction;

    public function __construct(PublishMqttAction $publishMqttAction)
    {
        $this->publishMqttAction = $publishMqttAction;
    }

    public function execute(string $deviceId, string $methodName, string $requestId, string $payload): void
    {
        $this->publishMqttAction->execute(
            'iotportal/' . $deviceId . '/methods/POST/' . $methodName . '/?$rid=' . $requestId,
            $payload
        );
    }
}
