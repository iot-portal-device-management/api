<?php

namespace App\Actions\DeviceCommandType;

use App\Models\DeviceCommandType;
use App\Models\Device;

class FindDeviceCommandTypeByNameForDeviceAction
{
    public function execute(string $deviceId, string $name): DeviceCommandType
    {
        return DeviceCommandType::name($name)->deviceId($deviceId)->firstOrFail();
//        return Device::id($deviceId)->commands()->name($name)->firstOrFail();
    }
}
