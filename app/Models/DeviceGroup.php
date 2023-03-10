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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kirschbaum\PowerJoins\PowerJoins;

class DeviceGroup extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
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
        'user_id',
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
        'user_id',
    ];

    /**
     * Get the user that owns the device group.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the devices for the device group.
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class);
    }

    /**
     * Get the device jobs for the device group.
     */
    public function deviceJobs(): HasMany
    {
        return $this->hasMany(DeviceJob::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where($this->qualifyColumn('id'), $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn($this->qualifyColumn('id'), $value);
    }

    public function scopeNameILike($query, $value)
    {
        return $query->where($this->qualifyColumn('name'), 'ILIKE', "%{$value}%");
    }

    public function scopeUserId($query, $value)
    {
        return $query->where($this->qualifyColumn('user_id'), $value);
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
