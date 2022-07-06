<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\HasDeviceConnectionKey;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, EloquentGetTableName, Uuid, HasDeviceConnectionKey;

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
     * Get the managed teams for the user.
     */
    public function managedTeams()
    {
        return $this->belongsToMany(Team::class)->wherePivot('role', 0);
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
     * Get all of the saved commands for the user.
     */
    public function savedCommands()
    {
        return $this->hasMany(SavedCommand::class);
    }

    /**
     * Get the owning devices for the user.
     */
    public function devices()
    {
        return $this->hasManyThrough(Device::class, DeviceCategory::class);
    }

    /**
     * Get the FOTA configurations for the user.
     */
    public function fotaConfigurations()
    {
        return $this->hasMany(FotaConfiguration::class);
    }

    /**
     * Get the AOTA configurations for the user.
     */
    public function aotaConfigurations()
    {
        return $this->hasMany(AotaConfiguration::class);
    }

    /**
     * Get the configuration files for the user.
     */
    public function configurationFiles()
    {
        return $this->hasMany(ConfigurationFile::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where('id', $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn('users.id', $value);
    }

    public function scopeNameLike($query, $value)
    {
        return $query->where('name', 'like', "%{$value}%");
    }
}
