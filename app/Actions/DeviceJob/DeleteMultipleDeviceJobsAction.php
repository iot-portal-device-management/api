<?php

namespace App\Actions\DeviceJob;

use App\Models\DeviceJob;

class DeleteMultipleDeviceJobsAction
{
    public function execute(array $ids): bool
    {
        return DeviceJob::idIn($ids)->delete();
    }
}
