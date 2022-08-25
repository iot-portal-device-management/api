<?php

namespace App\Traits;

trait EloquentTableHelpers
{
    public static function getTableName(): string
    {
        return (new static())->getTable();
    }
}
