<?php

namespace App\Actions\Device;

use App\Models\Device;

class UpdateDeviceByIdAction
{
    public function execute(array $data): bool
    {
        $device = [];

        if (isset($data['name'])) $device['name'] = $data['name'];
        if (isset($data['deviceCategoryId'])) $device['device_category_id'] = $data['deviceCategoryId'];

        return Device::id($data['deviceId'])->update($device);
    }
}
