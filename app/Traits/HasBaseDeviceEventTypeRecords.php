<?php

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
