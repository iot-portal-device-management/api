<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\HasBaseDeviceCommandTypeRecords;
use App\Traits\HasBaseDeviceEventTypeRecords;
use App\Traits\HasMqttCredentials;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory,
        EloquentGetTableName,
        Uuid,
        HasMqttCredentials,
        HasBaseDeviceCommandTypeRecords,
        HasBaseDeviceEventTypeRecords;

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
        return 'Device not found.';
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
     * Get the device command types for the device.
     */
    public function deviceCommandTypes()
    {
        return $this->hasMany(DeviceCommandType::class);
    }

    /**
     * Get all of the device commands for the device.
     */
    public function deviceCommands()
    {
        return $this->hasManyThrough(DeviceCommand::class, DeviceCommandType::class);
    }

    /**
     * Get the device event types for the device.
     */
    public function deviceEventTypes()
    {
        return $this->hasMany(DeviceEventType::class);
    }

    /**
     * Get the device events for the device.
     */
    public function deviceEvents()
    {
        return $this->hasManyThrough(DeviceEvent::class, DeviceEventType::class);
    }

    /**
     * Get the device temperature statistics for the device.
     */
    public function deviceTemperatureStatistics()
    {
        return $this->hasMany(DeviceTemperatureStatistic::class);
    }

    /**
     * Get the device memory statistics for the device.
     */
    public function deviceMemoryStatistics()
    {
        return $this->hasMany(DeviceMemoryStatistic::class);
    }

    /**
     * Get the device disk statistics for the device.
     */
    public function deviceDiskStatistics()
    {
        return $this->hasMany(DeviceDiskStatistic::class);
    }

    /**
     * Get the device network statistics for the device.
     */
    public function deviceNetworkStatistics()
    {
        return $this->hasMany(DeviceNetworkStatistic::class);
    }

    /**
     * Get the device container statistics for the device.
     */
    public function deviceContainerStatistics()
    {
        return $this->hasMany(DeviceContainerStatistic::class);
    }

    /**
     * Get the device CPU statistics for the device.
     */
    public function deviceCpuStatistics()
    {
        return $this->hasMany(DeviceCpuStatistic::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where($this->getTable() . '.id', $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn($this->getTable() . '.id', $value);
    }

    public function scopeExcludeId($query, $value)
    {
        return $query->where($this->getTable() . '.id', '!=', $value);
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
