<?php

namespace App\Actions\Device;

use App\Models\Device;

class UpdateDeviceAction
{
    public function execute(string $id, array $data): bool
    {
        $newDevice = [];

        if (isset($data['name'])) $newDevice['name'] = $data['name'];
        if (isset($data['deviceCategoryId'])) $newDevice['device_category_id'] = $data['deviceCategoryId'];

        return Device::id($id)->update($newDevice);
    }
}
