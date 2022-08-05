<?php

namespace App\Actions\Device;

use App\Models\Device;
use App\Models\DeviceStatus;
use App\Models\User;

class CreateDeviceAction
{
    public function execute(User $user, array $data = [])
    {
        return Device::create([
            'name' => $data['name'] ?? null,
            'device_category_id' => $data['deviceCategoryId'] ?? $user->deviceCategories()->getUncategorized()->id,
            'device_status_id' => DeviceStatus::getRegistered()->id,
        ]);
    }
}
