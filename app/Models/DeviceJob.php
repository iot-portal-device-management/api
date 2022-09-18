<?php

namespace App\Models;

use App\Traits\EloquentTableHelpers;
use App\Traits\HasDefaultDeviceJobStatus;
use App\Traits\Searchable;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kirschbaum\PowerJoins\PowerJoins;

class DeviceJob extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid, HasDefaultDeviceJobStatus;

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
        'device_group_id',
        'saved_device_command_id',
        'device_job_status_id',
        'device_job_error_type_id',
        'job_id',
        'job_batch_id',
        'started_at',
        'completed_at',
        'failed_at',
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
        'device_group_id',
        'saved_device_command_id',
        'device_job_status_id',
        'device_job_error_type_id',
        'job_id',
        'job_batch_id',
    ];

    /**
     * Get the device job status that owns the device job.
     */
    public function deviceJobStatus(): BelongsTo
    {
        return $this->belongsTo(DeviceJobStatus::class);
    }

    /**
     * Get the user that owns the device job.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the device group that owns the device job.
     */
    public function deviceGroup(): BelongsTo
    {
        return $this->belongsTo(DeviceGroup::class);
    }

    /**
     * Get the saved device command for the device job.
     */
    public function savedDeviceCommand(): BelongsTo
    {
        return $this->belongsTo(SavedDeviceCommand::class);
    }

    /**
     * Get the device commands for the device job.
     */
    public function deviceCommands(): HasMany
    {
        return $this->hasMany(DeviceCommand::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where($this->qualifyColumn('id'), $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn($this->qualifyColumn('id'), $value);
    }

    public function scopeNameLike($query, $value)
    {
        return $query->where($this->qualifyColumn('name'), 'LIKE', "%{$value}%");
    }

    public function scopeOfStatus($query, $value)
    {
        return $query->whereHas('deviceJobStatus', function (Builder $query) use ($value) {
            $query->ofStatus($value);
        });
    }

    public function scopeUserId($query, $value)
    {
        return $query->where($this->qualifyColumn('user_id'), $value);
    }

    public function scopeStartedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->qualifyColumn('started_at'), $dates);
    }

    public function scopeCompletedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->qualifyColumn('completed_at'), $dates);
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
