<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\HasDefaultDeviceCommandStatus;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceCommand extends Model
{
    use HasFactory, EloquentGetTableName, Uuid, HasDefaultDeviceCommandStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payload',
        'job_id',
        'device_command_error_type_id',
        'device_command_status_id',
        'device_command_type_id',
        'device_job_id',
        'started_at',
        'completed_at',
        'failed_at',
        'responded_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the device command status that owns the device command.
     */
    public function deviceCommandStatus()
    {
        return $this->belongsTo(DeviceCommandStatus::class);
    }

    /**
     * Get the device command type that owns the device command.
     */
    public function deviceCommandType()
    {
        return $this->belongsTo(DeviceCommandType::class);
    }

    /**
     * Get the device job that owns the device command.
     */
    public function deviceJob()
    {
        return $this->belongsTo(DeviceJob::class);
    }

    public function scopePayloadLike($query, $value)
    {
        return $query->where($this->getTable() . '.payload', 'LIKE', "%{$value}%");
    }

    public function scopeDeviceCommandTypeId($query, $value)
    {
        return $query->where($this->getTable() . '.device_command_type_id', $value);
    }

    public function scopeRespondedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->getTable() . '.responded_at', $dates);
    }

    public function scopeCreatedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->getTable() . '.created_at', $dates);
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->whereHas('deviceCommandType', function (Builder $query) use ($value) {
            $query->deviceId($value);
        });
    }
}
