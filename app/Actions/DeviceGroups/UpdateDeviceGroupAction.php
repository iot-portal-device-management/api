<?php

namespace App\Actions\DeviceGroups;

use App\Models\DeviceGroup;

class UpdateDeviceGroupAction
{
    public function execute(DeviceGroup $deviceGroup, array $data): bool
    {
        $success = $deviceGroup->update([
            'name' => $data['name'] ?? $deviceGroup->name,
        ]);

        $deviceGroup->devices()->sync($data['devices']);

        return $success;
    }
}
