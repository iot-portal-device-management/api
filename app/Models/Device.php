<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\HasBaseCommandRecords;
use App\Traits\HasBaseEventRecords;
use App\Traits\HasMqttCredentials;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory, EloquentGetTableName, Uuid, HasMqttCredentials, HasBaseCommandRecords, HasBaseEventRecords;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'bios_release_date',
        'bios_vendor',
        'bios_version',
        'cpu',
        'disk_information',
        'os_information',
        'system_manufacturer',
        'system_product_name',
        'total_memory',
        'mqtt_password',
        'last_seen',
        'device_category_id',
        'device_status_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'laravel_through_key',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->name === null) {
                $model->setAttribute('name', $model->getKey());
            }
        });
    }

    public function notFoundMessage()
    {
        return 'Device id not found.';
    }

    /**
     * Get the device category for the device.
     */
    public function deviceCategory()
    {
        return $this->belongsTo(DeviceCategory::class);
    }

    /**
     * Get the device groups for the device.
     */
    public function deviceGroups()
    {
        return $this->belongsToMany(DeviceGroup::class);
    }

    /**
     * Get the teams that can access the device.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Get the device status for the device.
     */
    public function deviceStatus()
    {
        return $this->belongsTo(DeviceStatus::class);
    }

    /**
     * Get the commands for the device.
     */
    public function commands()
    {
        return $this->hasMany(Command::class);
    }

    /**
     * Get all of the command histories for the device.
     */
    public function commandHistories()
    {
        return $this->hasManyThrough(CommandHistory::class, Command::class);
    }

    /**
     * Get the events for the device.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the event histories for the device.
     */
    public function eventHistories()
    {
        return $this->hasManyThrough(EventHistory::class, Event::class);
    }

    /**
     * Get the temperature statistics for the device.
     */
    public function temperatureStatistics()
    {
        return $this->hasMany(TemperatureStatistic::class);
    }

    /**
     * Get the memory statistics for the device.
     */
    public function memoryStatistics()
    {
        return $this->hasMany(MemoryStatistic::class);
    }

    /**
     * Get the disk statistics for the device.
     */
    public function diskStatistics()
    {
        return $this->hasMany(DiskStatistic::class);
    }

    /**
     * Get the network statistics for the device.
     */
    public function networkStatistics()
    {
        return $this->hasMany(NetworkStatistic::class);
    }

    /**
     * Get the container statistics for the device.
     */
    public function containerStatistics()
    {
        return $this->hasMany(ContainerStatistic::class);
    }

    /**
     * Get the cpu statistics for the device.
     */
    public function cpuStatistics()
    {
        return $this->hasMany(CpuStatistic::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where($this->getTable() . '.id', $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn($this->getTable() . '.id', $value);
    }

    public function scopeName($query, $value)
    {
        return $query->where($this->getTable() . '.name', $value);
    }

    public function scopeNameILike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'ILIKE', "%{$value}%");
    }

    public function scopeBiosVendorILike($query, $value)
    {
        return $query->where($this->getTable() . '.bios_vendor', 'ILIKE', "%{$value}%");
    }

    public function scopeBiosVersionILike($query, $value)
    {
        return $query->where($this->getTable() . '.bios_version', 'ILIKE', "%{$value}%");
    }

    public function scopeDeviceCategoryId($query, $value)
    {
        return $query->where($this->getTable() . '.device_category_id', $value);
    }

    public function scopeDeviceStatusId($query, $value)
    {
        return $query->where($this->getTable() . '.device_status_id', $value);
    }

    public function scopeDeviceGroupId($query, $value)
    {
        return $query->whereHas('deviceGroups', function (Builder $query) use ($value) {
            $query->where('device_groups.id', $value);
        });
    }

    public function scopeDeviceGroupUniqueId($query, $value)
    {
        return $query->whereHas('deviceGroups', function (Builder $query) use ($value) {
            $query->where('device_groups.unique_id', $value);
        });
    }

    public function scopeExcludeId($query, $value)
    {
        return $query->where($this->getTable() . '.id', '!=', $value);
    }

    public function scopeUserId($query, $value)
    {
        return $query->whereHas('deviceCategory', function (Builder $query) use ($value) {
            $query->userId($value);
        });
    }

    public function isRegistered()
    {
        return $this->deviceStatus->name === DeviceStatus::STATUS_REGISTERED;
    }

    public function isOnline()
    {
        return $this->deviceStatus->name === DeviceStatus::STATUS_ONLINE;
    }

    public function isOffline()
    {
        return $this->deviceStatus->name === DeviceStatus::STATUS_OFFLINE;
    }
}
