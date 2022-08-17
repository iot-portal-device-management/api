<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\HasDefaultDeviceJobStatus;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceJob extends Model
{
    use HasFactory, EloquentGetTableName, Uuid, HasDefaultDeviceJobStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'device_group_id',
        'saved_device_command_id',
        'device_job_status_id',
        'device_job_error_type_id',
        'job_id',
        'job_batch_id',
        'started_at',
        'completed_at',
        'failed_at',
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
    ];

    /**
     * Get the device job status that owns the device job.
     */
    public function deviceJobStatus()
    {
        return $this->belongsTo(DeviceJobStatus::class);
    }

    /**
     * Get the user that owns the device job.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the device group that owns the device job.
     */
    public function deviceGroup()
    {
        return $this->belongsTo(DeviceGroup::class);
    }

    /**
     * Get the saved device command for the device job.
     */
    public function savedDeviceCommand()
    {
        return $this->belongsTo(SavedDeviceCommand::class);
    }

    /**
     * Get the device commands for the device job.
     */
    public function deviceCommands()
    {
        return $this->hasMany(DeviceCommand::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where($this->getTable() . '.id', $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn($this->getTable() . '.id', $value);
    }

    public function scopeNameLike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'LIKE', "%{$value}%");
    }

    public function scopeOfStatus($query, $value)
    {
        return $query->whereHas('deviceJobStatus', function (Builder $query) use ($value) {
            $query->ofStatus($value);
        });
    }

    public function scopeUserId($query, $value)
    {
        return $query->where($this->getTable() . '.user_id', $value);
    }

    public function scopeStartedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->getTable() . '.started_at', $dates);
    }

    public function scopeCompletedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->getTable() . '.completed_at', $dates);
    }

    public function scopeDeviceGroupNameILike($query, $value)
    {
        return $query->whereHas('deviceGroup', function (Builder $query) use ($value) {
            $query->nameILike($value);
        });
    }

    public function scopeSavedDeviceCommandNameILike($query, $value)
    {
        return $query->whereHas('savedDeviceCommand', function (Builder $query) use ($value) {
            $query->nameILike($value);
        });
    }
}
