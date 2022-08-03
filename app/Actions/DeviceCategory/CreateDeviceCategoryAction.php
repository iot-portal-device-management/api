<?php

namespace App\Actions\DeviceCategory;

use App\Models\Device;
use App\Models\DeviceCategory;
use App\Models\User;

class CreateDeviceCategoryAction
{
    public function execute(User $user, array $data): DeviceCategory
    {
        $deviceCategory = DeviceCategory::create([
            'name' => $data['name'],
            'user_id' => $user->id,
        ]);

        if (isset($data['deviceIds'])) {
            Device::idIn($data['deviceIds'])->update([
                'device_category_id' => $deviceCategory->id,
            ]);
        }

        return $deviceCategory;
    }
}
