<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceTemperatureStatistic extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'temperature',
    ];

    /**
     * Get the device that owns the device temperature statistic.
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->where($this->getTable() . '.device_id', $value);
    }
}
