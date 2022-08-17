<?php

namespace App\Traits;

use App\Models\DeviceJobStatus;

trait HasDefaultDeviceJobStatus
{
    /**
     * Bootstrap the model with default device job status.
     *
     * @return void
     */
    protected static function bootHasDefaultDeviceJobStatus()
    {
        /**
         * Listen for the creating event on the DeviceJob model.
         * Sets the 'device_job_status_id' on the instance being created
         */
        static::creating(function ($model) {
            if ($model->device_job_status_id === null) {
                $pendingDeviceJobStatusId = DeviceJobStatus::getStatus(DeviceJobStatus::STATUS_PENDING)->id;

                $model->setAttribute('device_job_status_id', $pendingDeviceJobStatusId);
            }
        });
    }
}
