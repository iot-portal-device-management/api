<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceJob;

use App\Models\DeviceJob;

class CreateDeviceJobAction
{
    public function execute(array $data): DeviceJob
    {
        return DeviceJob::create([
            'name' => $data['name'],
            'user_id' => $data['userId'],
            'device_group_id' => $data['deviceGroupId'],
            'saved_device_command_id' => $data['savedDeviceCommandId'],
        ]);
    }
}
