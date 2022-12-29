<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCategory;

use App\Models\Device;

class UpdateDeviceCategoryByIdAction
{
    private FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction;

    public function __construct(FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction)
    {
        $this->findDeviceCategoryByIdAction = $findDeviceCategoryByIdAction;
    }

    public function execute(array $data): bool
    {
        $deviceCategory = $this->findDeviceCategoryByIdAction->execute($data['deviceCategoryId']);

        if (isset($data['name'])) {
            $deviceCategory->update([
                'name' => $data['name'],
            ]);
        }

        if (isset($data['deviceIds']) && $data['deviceIds']) {
            Device::idIn($data['deviceIds'])->update([
                'device_category_id' => $data['deviceCategoryId'],
            ]);
        }

        return true;
    }
}
