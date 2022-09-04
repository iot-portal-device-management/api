<?php

namespace App\Models;

use App\Traits\EloquentTableHelpers;
use App\Traits\Searchable;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class DeviceStatus extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid;

    const STATUS_REGISTERED = 'REGISTERED';
    const STATUS_ONLINE = 'ONLINE';
    const STATUS_OFFLINE = 'OFFLINE';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
    ];

    /**
     * Get the devices for the device status.
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
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

    public function scopeNameLike($query, $value)
    {
        return $query->where($this->qualifyColumn('name'), 'LIKE', "%{$value}%");
    }

    public function scopeOfStatus($query, $value)
    {
        return $query->name($value);
    }

    public function scopeGetStatus($query, $value)
    {
        return $query->ofStatus($value)->firstOrFail();
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
