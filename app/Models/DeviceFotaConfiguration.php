<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Models;

use App\Traits\EloquentTableHelpers;
use App\Traits\Searchable;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kirschbaum\PowerJoins\PowerJoins;

class DeviceFotaConfiguration extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid;

    /**
     * Get the user that owns the device FOTA configuration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
