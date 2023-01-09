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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kirschbaum\PowerJoins\PowerJoins;

class DeviceCommandType extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'method_name',
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
        'method_name',
        'device_id',
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
        'method_name',
        'device_id',
    ];

    /**
     * Get the device that owns the device command type.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the device commands for the device command type.
     */
    public function deviceCommands(): HasMany
    {
        return $this->hasMany(DeviceCommand::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where($this->qualifyColumn('id'), $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn($this->qualifyColumn('id'), $value);
    }

    public function scopeName($query, $value)
    {
        return $query->where($this->qualifyColumn('name'), $value);
    }

    public function scopeNameILike($query, $value)
    {
        return $query->where($this->qualifyColumn('name'), 'ILIKE', "%{$value}%");
    }

    public function scopeMethodName($query, $value)
    {
        return $query->where($this->qualifyColumn('method_name'), $value);
    }

    public function scopeMethodNameILike($query, $value)
    {
        return $query->where($this->qualifyColumn('method_name'), 'ILIKE', "%{$value}%");
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->where($this->qualifyColumn('device_id'), $value);
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
