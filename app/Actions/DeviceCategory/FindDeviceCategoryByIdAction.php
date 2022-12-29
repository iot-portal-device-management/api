<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCategory;

use App\Models\DeviceCategory;

class FindDeviceCategoryByIdAction
{
    public function execute(string $id): DeviceCategory
    {
        return DeviceCategory::findOrFail($id);
    }
}
