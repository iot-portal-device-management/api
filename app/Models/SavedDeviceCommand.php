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

    public function scopeNameLike($query, $value)
    {
        return $query->where($this->getTable() . '.name', 'LIKE', "%{$value}%");
    }

    public function scopeCommandNameLike($query, $value)
    {
        return $query->where($this->getTable() . '.command_name', 'LIKE', "%{$value}%");
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
