<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceJob;

use App\Models\DeviceJob;

class FindDeviceJobByIdAction
{
    public function execute(string $id): DeviceJob
    {
        return DeviceJob::id($id)->with('deviceJobStatus:id,name')->firstOrFail();
    }
}
