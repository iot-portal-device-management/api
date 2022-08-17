<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceJobErrorType extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    const TYPE_MQTT_BROKER_CONNECTION_REFUSED = 'MQTT_BROKER_CONNECTION_REFUSED';
    const TYPE_DEVICE_TIMEOUT = 'DEVICE_TIMEOUT';
    const TYPE_OTHERS = 'OTHERS';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'error_code',
        'description',
    ];

    /**
     * Get the device events for the device event type.
     */
    public function deviceJobs()
    {
        return $this->hasMany(DeviceJob::class);
    }

    public function scopeName($query, $value)
    {
        return $query->where($this->getTable() . '.name', $value);
    }

    public function scopeNameLike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'LIKE', "%{$value}%");
    }

    public function scopeNameILike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'ILIKE', "%{$value}%");
    }

    public function scopeOfType($query, $value)
    {
        return $this->name($value);
    }

    public function scopeGetType($query, $value)
    {
        return $this->ofType($value)->firstOrFail();
    }
}
