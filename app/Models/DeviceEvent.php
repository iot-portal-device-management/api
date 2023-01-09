<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Models;

use App\Traits\EloquentTableHelpers;
use App\Traits\Searchable;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kirschbaum\PowerJoins\PowerJoins;

class DeviceEvent extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'raw_data',
        'device_event_type_id',
    ];

    /**
     * The attributes that are sortable.
     *
     * JSON columns cannot be sorted at the moment.
     *
     * @var array
     */
    protected array $sortableColumns = [
        'id',
        'raw_data',
        'device_event_type_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are filterable.
     *
     * Timestamp columns cannot be filtered at the moment.
     *
     * @var array
     */
    protected array $filterableColumns = [
        'id',
        'raw_data',
        'device_event_type_id',
    ];

    /**
     * Get the device event type that owns the device event.
     */
    public function deviceEventType(): BelongsTo
    {
        return $this->belongsTo(DeviceEventType::class);
    }

    public function scopeRawDataLike($query, $value)
    {
        return $query->where($this->qualifyColumn('raw_data'), 'LIKE', "%{$value}%");
    }

    public function scopeDeviceEventTypeId($query, $value)
    {
        return $query->where($this->qualifyColumn('device_event_type_id'), $value);
    }

    public function scopeCreatedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->qualifyColumn('created_at'), $dates);
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->whereHas('deviceEventType', function (Builder $query) use ($value) {
            $query->deviceId($value);
        });
    }
}
