<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Policies;

use App\Models\DeviceGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DeviceGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewIndex(User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param DeviceGroup $deviceGroup
     * @return Response|bool
     */
    public function view(User $user, DeviceGroup $deviceGroup): Response|bool
    {
        return $user->id === $deviceGroup->user_id;
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
     * @param DeviceGroup $deviceGroup
     * @return Response|bool
     */
    public function update(User $user, DeviceGroup $deviceGroup): Response|bool
    {
        return $user->id === $deviceGroup->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param DeviceGroup $deviceGroup
     * @return Response|bool
     */
    public function delete(User $user, DeviceGroup $deviceGroup): Response|bool
    {
        return $user->id === $deviceGroup->user_id;
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
        return $user->deviceGroups->whereIn('id', $ids)->count() === count($ids);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param DeviceGroup $deviceGroup
     * @return Response|bool
     */
    public function restore(User $user, DeviceGroup $deviceGroup): Response|bool
    {
        return $user->id === $deviceGroup->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param DeviceGroup $deviceGroup
     * @return Response|bool
     */
    public function forceDelete(User $user, DeviceGroup $deviceGroup): Response|bool
    {
        return $user->id === $deviceGroup->user_id;
    }
}
