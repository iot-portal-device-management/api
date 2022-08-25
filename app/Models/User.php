<?php

namespace App\Models;

use App\Traits\EloquentTableHelpers;
use App\Traits\HasDeviceConnectionKey;
use App\Traits\Searchable;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kirschbaum\PowerJoins\PowerJoins;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        PowerJoins,
        Searchable,
        EloquentTableHelpers,
        Uuid,
        HasDeviceConnectionKey;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
        'email',
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
        'email',
    ];

    /**
     * Get the managed teams for the user.
     */
    public function managedTeams()
    {
        return $this->belongsToMany(Team::class)
            ->wherePivot('role', 0);
    }

    /**
     * The teams that the user joins.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Get all of the device jobs for the user.
     */
    public function deviceJobs()
    {
        return $this->hasMany(DeviceJob::class);
    }

    /**
     * Get all of the device categories for the user.
     */
    public function deviceCategories()
    {
        return $this->hasMany(DeviceCategory::class);
    }

    /**
     * Get all of the device groups for the user.
     */
    public function deviceGroups()
    {
        return $this->hasMany(DeviceGroup::class);
    }

    /**
     * Get all of the saved device commands for the user.
     */
    public function savedDeviceCommands()
    {
        return $this->hasMany(SavedDeviceCommand::class);
    }

    /**
     * Get the owning devices for the user.
     */
    public function devices()
    {
        return $this->hasManyThrough(Device::class, DeviceCategory::class);
    }

    /**
     * Get the device FOTA configurations for the user.
     */
    public function deviceFotaConfigurations()
    {
        return $this->hasMany(DeviceFotaConfiguration::class);
    }

    /**
     * Get the device AOTA configurations for the user.
     */
    public function deviceAotaConfigurations()
    {
        return $this->hasMany(DeviceAotaConfiguration::class);
    }

    /**
     * Get the device configuration files for the user.
     */
    public function deviceConfigurationFiles()
    {
        return $this->hasMany(DeviceConfigurationFile::class);
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
}
