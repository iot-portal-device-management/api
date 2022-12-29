<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\Device;

use App\Models\Device;
use App\Models\DeviceCategory;
use App\Models\DeviceStatus;
use App\Models\User;

class CreateDeviceAction
{
    public function execute(array $data = []): Device
    {
        return Device::create([
            'name' => $data['name'] ?? null,
            'device_category_id' => $data['deviceCategoryId'] ?? User::findOrFail($data['userId'])
                    ->deviceCategories()
                    ->getCategory(DeviceCategory::CATEGORY_UNCATEGORIZED)
                    ->id,
            'device_status_id' => DeviceStatus::getStatus(DeviceStatus::STATUS_REGISTERED)->id,
        ]);
    }
}
