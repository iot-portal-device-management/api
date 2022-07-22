<?php

namespace App\Actions\Devices;

use App\Models\Device;
use App\Models\DeviceStatus;
use App\Models\User;

class CreateDeviceAction
{
    public function execute(User $user, array $data = [])
    {
        return Device::create([
            'name' => $data['name'] ?? null,
            'device_category_id' => $data['deviceCategory'] ?? $user->deviceCategories()->getUncategorized()->id,
            'device_status_id' => DeviceStatus::getRegistered()->id,
        ]);
    }
}
