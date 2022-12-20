<?php

namespace App\Actions\Device;

use App\Models\Device;

class FindDeviceByIdByUserIdAction
{
    public function execute(array $data): Device
    {
        return Device::id($data['deviceId'])->userId($data['userId'])->firstOrFail();
    }
}
