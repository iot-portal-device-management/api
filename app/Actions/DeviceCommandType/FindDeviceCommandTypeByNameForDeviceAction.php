<?php

namespace App\Actions\DeviceCommandType;

use App\Models\DeviceCommandType;

class FindDeviceCommandTypeByNameForDeviceAction
{
    public function execute(string $deviceId, string $name): DeviceCommandType
    {
        return DeviceCommandType::name($name)->deviceId($deviceId)->firstOrFail();
    }
}
