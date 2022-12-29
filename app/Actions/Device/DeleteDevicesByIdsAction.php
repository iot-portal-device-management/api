<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\Device;

use App\Models\Device;

class DeleteDevicesByIdsAction
{
    public function execute(array $ids): bool
    {
        return Device::idIn($ids)->delete();
    }
}
