<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCommand;

use App\Models\DeviceCommand;
use App\Models\DeviceCommandStatus;

class MarkDeviceCommandAsCompletedAction
{
    public function execute(DeviceCommand $deviceCommand): bool
    {
        return $deviceCommand->update([
            'completed_at' => now(),
            'responded_at' => now(),
            'device_command_status_id' => DeviceCommandStatus::getStatus(DeviceCommandStatus::STATUS_SUCCESSFUL)->id,
        ]);
    }
}
