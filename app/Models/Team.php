<?php

namespace App\Models;

use App\Traits\EloquentTableHelpers;
use App\Traits\Searchable;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Team extends Model
{
    use HasFactory, PowerJoins, Searchable, EloquentTableHelpers, Uuid;

    /**
     * Get the admins that owns the team.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class)->wherePivot('role', 0);
    }

    /**
     * The users that joined the team.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the devices for the team.
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
