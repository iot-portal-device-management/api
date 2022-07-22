<?php

namespace App\Actions\Devices;

use App\Models\Device;

class UpdateDeviceAction
{
    public function execute(string $id, array $data): bool
    {
        $newDevice = [];

        if ($data['name']) $newDevice['name'] = $data['name'];
        if ($data['deviceCategory']) $newDevice['device_category_id'] = $data['deviceCategory'];

        return Device::id($id)->update($newDevice);
    }
}
