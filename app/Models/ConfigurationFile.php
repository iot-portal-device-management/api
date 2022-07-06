<?php

namespace App\Models;

use App\Traits\EloquentGetTableName;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigurationFile extends Model
{
    use HasFactory, EloquentGetTableName, Uuid;

    /**
     * Get the user that owns the configuration file.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
