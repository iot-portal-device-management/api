<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUniqueId
{
    public static function uniqueIdExists($value)
    {
        return static::where('unique_id', $value)->exists();
    }

    public static function generateUniqueId(): string
    {
        $uniqueId = Str::uuid()->toString();

        if (static::uniqueIdExists($uniqueId)) {
            return static::generateUniqueId();
        }

        return $uniqueId;
    }
}
