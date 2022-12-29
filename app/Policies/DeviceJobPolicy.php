<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Policies;

use App\Models\DeviceJob;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DeviceJobPolicy
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
     * @param DeviceJob $deviceJob
     * @return Response|bool
     */
    public function view(User $user, DeviceJob $deviceJob): Response|bool
    {
        return $user->id === $deviceJob->user_id;
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
     * @param DeviceJob $deviceJob
     * @return Response|bool
     */
    public function update(User $user, DeviceJob $deviceJob): Response|bool
    {
        return $user->id === $deviceJob->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param DeviceJob $deviceJob
     * @return Response|bool
     */
    public function delete(User $user, DeviceJob $deviceJob): Response|bool
    {
        return $user->id === $deviceJob->user_id;
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
        return $user->deviceJobs->whereIn('id', $ids)->count() === count($ids);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param DeviceJob $deviceJob
     * @return Response|bool
     */
    public function restore(User $user, DeviceJob $deviceJob): Response|bool
    {
        return $user->id === $deviceJob->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param DeviceJob $deviceJob
     * @return Response|bool
     */
    public function forceDelete(User $user, DeviceJob $deviceJob): Response|bool
    {
        return $user->id === $deviceJob->user_id;
    }
}
