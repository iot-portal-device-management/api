<?php

namespace App\Traits;

trait HasBaseCommandRecords
{
    protected static function bootHasBaseCommandRecords()
    {
        static::created(function ($model) {
            $commandRecords = config('constants.command_records');

            $model->commands()->createMany($commandRecords);
        });
    }
}
