<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceEventType extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    const EVENT_PROPERTY = 'PROPERTY';
    const EVENT_TELEMETRY = 'TELEMETRY';

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

    public function scopeProperty($query)
    {
        return $query->name(self::EVENT_PROPERTY);
    }

    public function scopeTelemetry($query)
    {
        return $query->name(self::EVENT_TELEMETRY);
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->where($this->getTable() . '.device_id', $value);
    }

    public function scopeGetProperty($query)
    {
        return $query->property()->firstOrFail();
    }

    public function scopeGetTelemetry($query)
    {
        return $query->telemetry()->firstOrFail();
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
