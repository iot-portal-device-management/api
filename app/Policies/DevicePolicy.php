<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DevicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Device $device
     * @return Response|bool
     */
    public function view(User $user, Device $device): Response|bool
    {
        return $user->id === $device->deviceCategory->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Device $device
     * @return Response|bool
     */
    public function update(User $user, Device $device): Response|bool
    {
        return $user->id === $device->deviceCategory->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Device $device
     * @return Response|bool
     */
    public function delete(User $user, Device $device): Response|bool
    {
        return $user->id === $device->deviceCategory->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return Response|bool
     */
    public function deleteMany(User $user)
    {
        $ids = request()->ids;
        return $user->devices->whereIn('id', $ids)->count() === count($ids);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Device $device
     * @return Response|bool
     */
    public function restore(User $user, Device $device): Response|bool
    {
        return $user->id === $device->deviceCategory->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Device $device
     * @return Response|bool
     */
    public function forceDelete(User $user, Device $device): Response|bool
    {
        return $user->id === $device->deviceCategory->user_id;
    }

    /**
     * Determine whether the user can trigger command for the device.
     *
     * @param User $user
     * @param Device $device
     * @return Response|bool
     */
    public function triggerCommand(User $user, Device $device)
    {
        return $user->id === $device->deviceCategory->user_id;
    }
}
