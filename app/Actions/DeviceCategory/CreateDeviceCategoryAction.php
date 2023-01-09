<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCategory;

use App\Models\Device;
use App\Models\DeviceCategory;

class CreateDeviceCategoryAction
{
    public function execute(array $data): DeviceCategory
    {
        $deviceCategory = DeviceCategory::create([
            'name' => $data['name'],
            'user_id' => $data['userId'],
        ]);

        if (isset($data['deviceIds']) && $data['deviceIds']) {
            Device::idIn($data['deviceIds'])->update([
                'device_category_id' => $deviceCategory->id,
            ]);
        }

        return $deviceCategory;
    }
}
