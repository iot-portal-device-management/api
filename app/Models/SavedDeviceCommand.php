<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedDeviceCommand extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'device_command_type_name',
        'payload',
        'user_id',
    ];

    /**
     * Get the user that owns the saved device command.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeId($query, $value)
    {
        return $query->where($this->getTable() . '.id', $value);
    }

    public function scopeIdIn($query, $value)
    {
        return $query->whereIn($this->getTable() . '.id', $value);
    }

    public function scopeNameILike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'ILIKE', "%{$value}%");
    }

    public function scopeDeviceCommandTypeNameLike($query, $value)
    {
        return $query->where($this->getTable() . '.device_command_type_name', 'LIKE', "%{$value}%");
    }

    public function scopeUserId($query, $value)
    {
        return $query->where($this->getTable() . '.user_id', $value);
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
