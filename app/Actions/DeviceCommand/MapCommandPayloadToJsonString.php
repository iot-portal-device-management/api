<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCommand;

use App\Helpers\Helper;

// TODO: remove this file cause not using anymore
class MapCommandPayloadToJsonString
{
    public function execute(string $commandName, ?array $payload): bool|string
    {
        $payloadJson = 'null';

        if (isset($payload)) {
            $payloadJson = json_encode(Helper::mapArrayKeyByArray($payload, config('constants.commands.' . $commandName . '.configuration_map')));
        }

        return $payloadJson;
    }
}
