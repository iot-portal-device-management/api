<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceGroup;

use App\Models\DeviceGroup;

class CreateDeviceGroupAction
{
    public function execute(array $data)
    {
        $deviceGroup = DeviceGroup::create([
            'name' => $data['name'],
            'user_id' => $data['userId'],
        ]);

        if (isset($data['deviceIds']) && $data['deviceIds']) {
            $deviceRows = [];

            foreach ($data['deviceIds'] as $deviceId) {
                $deviceRows[$deviceId] = ['id' => DeviceGroup::generateId()];
            }

            $deviceGroup->devices()->attach($deviceRows);
        }

        return $deviceGroup;
    }
}
