<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceGroup;

use App\Models\DeviceGroup;

class FindDeviceGroupByIdAction
{
    public function execute(string $id): DeviceGroup
    {
        return DeviceGroup::findOrFail($id);
    }
}
