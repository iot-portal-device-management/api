<?php

namespace App\Actions\DeviceGroup;

use App\Models\DeviceGroup;

class FindDeviceGroupByIdAction
{
    public function execute(string $id): DeviceGroup
    {
        return DeviceGroup::findOrFail($id);
    }
}
