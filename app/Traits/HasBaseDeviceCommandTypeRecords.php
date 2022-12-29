<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Traits;

trait HasBaseDeviceCommandTypeRecords
{
    protected static function bootHasBaseDeviceCommandTypeRecords()
    {
        static::created(function ($model) {
            $baseDeviceCommandTypeRecords = config('constants.base_device_command_type_records');

            $model->deviceCommandTypes()->createMany($baseDeviceCommandTypeRecords);
        });
    }
}
