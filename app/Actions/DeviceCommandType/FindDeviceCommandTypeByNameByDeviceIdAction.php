<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCommandType;

use App\Models\DeviceCommandType;

class FindDeviceCommandTypeByNameByDeviceIdAction
{
    public function execute(array $data): DeviceCommandType
    {
        return DeviceCommandType::name($data['deviceCommandTypeName'])->deviceId($data['deviceId'])->firstOrFail();
    }
}
