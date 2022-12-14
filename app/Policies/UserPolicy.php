<?php

namespace App\Policies;

use App\Models\DeviceCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewOverallStatistic(User $user): Response|bool
    {
        return true;
    }
}
