<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventHistory extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'raw_data',
        'event_id',
    ];

    /**
     * Get the event that owns the event history.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeRawDataLike($query, $value)
    {
        return $query->where($this->getTable() . '.raw_data', 'like', "%{$value}%");
    }

    public function scopeEventId($query, $value)
    {
        return $query->where($this->getTable() . '.event_id', $value);
    }

    public function scopeCreatedAtBetween($query, $dates)
    {
        return $query->whereBetween($this->getTable() . '.created_at', $dates);
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->whereHas('event', function (Builder $query) use ($value) {
            $query->deviceId($value);
        });
    }
}
