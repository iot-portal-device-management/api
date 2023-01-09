<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceGroup;

use App\Models\DeviceGroup;

class DeleteDeviceGroupsByIdsAction
{
    public function execute(array $ids): bool
    {
        return DeviceGroup::idIn($ids)->delete();
    }
}
