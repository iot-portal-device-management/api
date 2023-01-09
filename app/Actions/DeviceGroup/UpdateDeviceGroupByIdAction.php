<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceGroup;

use App\Models\DeviceGroup;

class UpdateDeviceGroupByIdAction
{
    private FindDeviceGroupByIdAction $findDeviceGroupByIdAction;

    public function __construct(FindDeviceGroupByIdAction $findDeviceGroupByIdAction)
    {
        $this->findDeviceGroupByIdAction = $findDeviceGroupByIdAction;
    }

    public function execute(array $data): bool
    {
        $deviceGroup = $this->findDeviceGroupByIdAction->execute($data['deviceGroupId']);

        if (isset($data['name'])) {
            $deviceGroup->update([
                'name' => $data['name'],
            ]);
        }

        if (isset($data['deviceIds']) && $data['deviceIds']) {
            $deviceRows = [];

            foreach ($data['deviceIds'] as $deviceId) {
                $deviceRows[$deviceId] = ['id' => DeviceGroup::generateId()];
            }

            $deviceGroup->devices()->sync($deviceRows);
        }

        return true;
    }
}
