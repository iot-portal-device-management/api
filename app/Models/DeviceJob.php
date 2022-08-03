<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceJob extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'error',
        'job_batch_id',
        'started_at',
        'completed_at',
        'user_id',
        'device_group_id',
        'saved_device_command_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

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

    public function scopeStartedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->getTable() . '.started_at', $dates);
    }

    public function scopeCompletedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->getTable() . '.completed_at', $dates);
    }

    public function scopeUserId($query, $value)
    {
        return $query->where($this->getTable() . '.user_id', $value);
    }

    public function scopePending($query)
    {
        return $query->whereNull($this->getTable() . '.error')
            ->whereNull($this->getTable() . '.started_at')
            ->whereNull($this->getTable() . '.completed_at');
    }

    public function scopeProcessing($query)
    {
        return $query->whereNull($this->getTable() . '.error')
            ->whereNotNull($this->getTable() . '.started_at')
            ->whereNull($this->getTable() . '.completed_at');
    }

    public function scopeSuccessful($query)
    {
        return $query->whereNull($this->getTable() . '.error')
            ->whereNotNull($this->getTable() . '.started_at')
            ->whereNotNull($this->getTable() . '.completed_at');
    }

    public function scopeFailed($query)
    {
        return $query->whereNotNull($this->getTable() . '.error');
    }

    public function scopeStatus($query, $value)
    {
        if ($value === 'pending') return $query->pending();
        if ($value === 'processing') return $query->processing();
        if ($value === 'successful') return $query->successful();
        if ($value === 'failed') return $query->failed();
        return $query;
    }

    public function scopeDeviceGroupNameLike($query, $value)
    {
        return $query->whereHas('deviceGroup', function (Builder $query) use ($value) {
            $query->where('name', 'LIKE', "%{$value}%");
        });
    }

    public function scopeSavedCommandNameLike($query, $value)
    {
        return $query->whereHas('savedDeviceCommand', function (Builder $query) use ($value) {
            $query->where('name', 'LIKE', "%{$value}%");
        });
    }
}
