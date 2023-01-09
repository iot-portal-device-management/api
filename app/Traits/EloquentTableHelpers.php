<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Traits;

trait EloquentTableHelpers
{
    public static function getTableName(): string
    {
        return (new static())->getTable();
    }
}
