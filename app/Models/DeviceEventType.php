<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceEventType extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    const TYPE_PROPERTY = 'PROPERTY';
    const TYPE_TELEMETRY = 'TELEMETRY';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the device that owns the device event type.
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the device events for the device event type.
     */
    public function deviceEvents()
    {
        return $this->hasMany(DeviceEvent::class);
    }

    public function scopeName($query, $value)
    {
        return $query->where($this->getTable() . '.name', $value);
    }

    public function scopeNameLike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'LIKE', "%{$value}%");
    }

    public function scopeNameILike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'ILIKE', "%{$value}%");
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->where($this->getTable() . '.device_id', $value);
    }

    public function scopeOfType($query, $value)
    {
        return $this->name($value);
    }

    public function scopeGetType($query, $value)
    {
        return $this->ofType($value)->firstOrFail();
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
