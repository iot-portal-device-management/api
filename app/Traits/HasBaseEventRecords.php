<?php

namespace App\Traits;

trait HasBaseEventRecords
{
    protected static function bootHasBaseEventRecords()
    {
        static::created(function ($model) {
            $eventRecords = config('constants.event_records');

            $model->events()->createMany($eventRecords);
        });
    }
}
