<?php

namespace App\Actions\DeviceGroup;

use App\Models\DeviceGroup;
use App\Models\User;

class CreateDeviceGroupAction
{
    public function execute(User $user, array $data)
    {
        $deviceGroup = DeviceGroup::create([
            'name' => $data['name'],
            'user_id' => $user->id,
        ]);

        $deviceRows = [];

        foreach ($data['deviceIds'] as $deviceId) {
            $deviceRows[$deviceId] = ['id' => DeviceGroup::generateId()];
        }

        $deviceGroup->devices()->attach($deviceRows);

        return $deviceGroup;
    }
}
