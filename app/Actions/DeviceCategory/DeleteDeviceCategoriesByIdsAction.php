<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCategory;

use App\Models\DeviceCategory;

class DeleteDeviceCategoriesByIdsAction
{
    public function execute(array $ids): bool
    {
        return DeviceCategory::idIn($ids)->delete();
    }
}
