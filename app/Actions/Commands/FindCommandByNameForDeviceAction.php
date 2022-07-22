<?php

namespace App\Actions\Commands;

use App\Models\Command;
use App\Models\Device;

class FindCommandByNameForDeviceAction
{
    public function execute(string $deviceId, string $name): Command
    {
        return Command::name($name)->deviceId($deviceId)->firstOrFail();
//        return Device::id($deviceId)->commands()->name($name)->firstOrFail();
    }
}
