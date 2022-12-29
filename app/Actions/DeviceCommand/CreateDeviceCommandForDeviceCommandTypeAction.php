<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCommand;

use App\Models\DeviceCommand;

class CreateDeviceCommandForDeviceCommandTypeAction
{
    public function execute(array $data): DeviceCommand
    {
        return DeviceCommand::create([
            'payload' => $data['payload'] ?? null,
            'started_at' => $data['started_at'] ?? null,
            'completed_at' => $data['completed_at'] ?? null,
            'responded_at' => $data['responded_at'] ?? null,
            'device_command_status_id' => $data['device_command_status_id'] ?? null,
            'device_command_type_id' => $data['device_command_type_id'] ?? null,
            'device_job_id' => $data['device_job_id'] ?? null,
        ]);
    }
}
