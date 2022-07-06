<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceCategory extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    const CATEGORY_UNCATEGORIZED = 'UNCATEGORIZED';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'laravel_through_key',
    ];

    public function notFoundMessage()
    {
        return 'Device category not found.';
    }

    /**
     * Get the user that owns the device category.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the devices for the device category.
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
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

    public function scopeUserId($query, $value)
    {
        return $query->where($this->getTable() . '.user_id', $value);
    }

    public function scopeUncategorized()
    {
        return $this->name(self::CATEGORY_UNCATEGORIZED);
    }

    public function scopeGetUncategorized()
    {
        return $this->uncategorized()->firstOrFail();
    }

    public function scopeGetOptions($query)
    {
        return $query->get(['id as value', 'name as label']);
    }
}
