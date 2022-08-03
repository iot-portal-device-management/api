<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceEvent extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

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
     * Get the device event type that owns the device event.
     */
    public function deviceEventType()
    {
        return $this->belongsTo(DeviceEventType::class);
    }

    public function scopeRawDataLike($query, $value)
    {
        return $query->where($this->getTable() . '.raw_data', 'like', "%{$value}%");
    }

    public function scopeDeviceEventTypeId($query, $value)
    {
        return $query->where($this->getTable() . '.device_event_type_id', $value);
    }

    public function scopeCreatedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->getTable() . '.created_at', $dates);
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->whereHas('deviceEventType', function (Builder $query) use ($value) {
            $query->deviceId($value);
        });
    }
}
