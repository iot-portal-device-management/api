<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Traits;

trait HasBaseDeviceEventTypeRecords
{
    protected static function bootHasBaseDeviceEventTypeRecords()
    {
        static::created(function ($model) {
            $baseDeviceEventTypeRecords = config('constants.base_device_event_type_records');

            $model->deviceEventTypes()->createMany($baseDeviceEventTypeRecords);
        });
    }
}
