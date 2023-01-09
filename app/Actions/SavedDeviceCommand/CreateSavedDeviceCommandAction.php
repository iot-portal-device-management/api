<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\SavedDeviceCommand;

use App\Models\SavedDeviceCommand;

class CreateSavedDeviceCommandAction
{
    public function execute(array $data): SavedDeviceCommand
    {
        return SavedDeviceCommand::create([
            'name' => $data['name'],
            'device_command_type_name' => $data['deviceCommandTypeName'],
            'payload' => is_null($data['payload']) ? null : json_encode($data['payload']),
            'user_id' => $data['userId'],
        ]);
    }
}
