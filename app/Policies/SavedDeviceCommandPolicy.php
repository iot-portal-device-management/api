<?php

namespace App\Policies;

use App\Models\SavedDeviceCommand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SavedDeviceCommandPolicy
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
     * @param SavedDeviceCommand $savedDeviceCommand
     * @return Response|bool
     */
    public function view(User $user, SavedDeviceCommand $savedDeviceCommand): Response|bool
    {
        return $user->id === $savedDeviceCommand->user_id;
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
     * @param SavedDeviceCommand $savedDeviceCommand
     * @return Response|bool
     */
    public function update(User $user, SavedDeviceCommand $savedDeviceCommand): Response|bool
    {
        return $user->id === $savedDeviceCommand->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param SavedDeviceCommand $savedDeviceCommand
     * @return Response|bool
     */
    public function delete(User $user, SavedDeviceCommand $savedDeviceCommand): Response|bool
    {
        return $user->id === $savedDeviceCommand->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return Response|bool
     */
    public function deleteMany(User $user): Response|bool
    {
        $ids = request()->ids;
        return $user->savedDeviceCommands->whereIn('id', $ids)->count() === count($ids);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param SavedDeviceCommand $savedDeviceCommand
     * @return Response|bool
     */
    public function restore(User $user, SavedDeviceCommand $savedDeviceCommand): Response|bool
    {
        return $user->id === $savedDeviceCommand->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param SavedDeviceCommand $savedDeviceCommand
     * @return Response|bool
     */
    public function forceDelete(User $user, SavedDeviceCommand $savedDeviceCommand): Response|bool
    {
        return $user->id === $savedDeviceCommand->user_id;
    }
}
