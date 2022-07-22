<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'method_name',
    ];

    /**
     * Get the device that owns the command.
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the command histories for the command.
     */
    public function commandHistories()
    {
        return $this->hasMany(CommandHistory::class);
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

    public function scopeMethodName($query, $value)
    {
        return $query->where($this->getTable() . '.method_name', $value);
    }

    public function scopeMethodNameILike($query, $value)
    {
        return $query->where($this->getTable() . '.method_name', 'ILIKE', "%{$value}%");
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->where($this->getTable() . '.device_id', $value);
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
