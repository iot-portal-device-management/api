<?php

namespace App\Models;

use App\Traits\EloquentTableHelpers;
use App\Traits\Searchable;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kirschbaum\PowerJoins\PowerJoins;

class DeviceDiskStatistic extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'disk_usage_percentage',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'disk_usage_percentage' => 'float',
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
        'disk_usage_percentage',
        'device_id',
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
        'disk_usage_percentage',
        'device_id',
    ];

    /**
     * Get the device that owns the device disk statistic.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function scopeDeviceId($query, $value)
    {
        return $query->where($this->qualifyColumn('device_id'), $value);
    }
}
