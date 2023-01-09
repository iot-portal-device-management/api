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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kirschbaum\PowerJoins\PowerJoins;

class DeviceCommandErrorType extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid;

    const TYPE_MQTT_BROKER_CONNECTION_REFUSED = 'MQTT_BROKER_CONNECTION_REFUSED';
    const TYPE_DEVICE_COMMAND_TYPE_NOT_SUPPORTED = 'DEVICE_COMMAND_TYPE_NOT_SUPPORTED';
    const TYPE_DEVICE_TIMEOUT = 'DEVICE_TIMEOUT';
    const TYPE_OTHERS = 'OTHERS';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'error_code',
        'description',
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
        'name',
        'error_code',
        'description',
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
        'name',
        'error_code',
        'description',
    ];

    /**
     * Get the device commands for the device command type.
     */
    public function deviceCommands(): HasMany
    {
        return $this->hasMany(DeviceCommand::class);
    }

    public function scopeName($query, $value)
    {
        return $query->where($this->qualifyColumn('name'), $value);
    }

    public function scopeNameLike($query, $value)
    {
        return $query->where($this->qualifyColumn('name'), 'LIKE', "%{$value}%");
    }

    public function scopeNameILike($query, $value)
    {
        return $query->where($this->qualifyColumn('name'), 'ILIKE', "%{$value}%");
    }

    public function scopeOfType($query, $value)
    {
        return $query->name($value);
    }

    public function scopeGetType($query, $value)
    {
        return $query->ofType($value)->firstOrFail();
    }
}
