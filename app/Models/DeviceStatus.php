<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceStatus extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

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
     * Get the devices for the device status.
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where($this->getTable() . '.id', $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn($this->getTable() . '.id', $value);
    }

    public function scopeName($query, $value)
    {
        return $query->where($this->getTable() . '.name', $value);
    }

    public function scopeNameLike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'LIKE', "%{$value}%");
    }

    public function scopeOfStatus($query, $value)
    {
        return $this->name($value);
    }

    public function scopeGetStatus($query, $value)
    {
        return $this->ofStatus($value)->firstOrFail();
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
