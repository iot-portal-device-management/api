<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Traits;

use App\Models\DeviceCommandStatus;

trait HasDefaultDeviceCommandStatus
{
    /**
     * Bootstrap the model with default device command status.
     *
     * @return void
     */
    protected static function bootHasDefaultDeviceCommandStatus()
    {
        /**
         * Listen for the creating event on the DeviceCommand model.
         * Sets the 'device_command_status_id' on the instance being created
         */
        static::creating(function ($model) {
            if ($model->device_command_status_id === null) {
                $pendingDeviceCommandStatusId = DeviceCommandStatus::getStatus(DeviceCommandStatus::STATUS_PENDING)->id;

                $model->setAttribute('device_command_status_id', $pendingDeviceCommandStatusId);
            }
        });
    }
}
