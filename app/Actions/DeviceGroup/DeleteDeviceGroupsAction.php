<?php

namespace App\Actions\DeviceGroup;

use App\Models\DeviceGroup;

class DeleteDeviceGroupsAction
{
    public function execute(array $ids): bool
    {
        return DeviceGroup::idIn($ids)->delete();
    }
}
