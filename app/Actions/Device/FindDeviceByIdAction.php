<?php

namespace App\Actions\Device;

use App\Models\Device;

class FindDeviceByIdAction
{
    public function execute(string $id): Device
    {
        return Device::id($id)->with(
            'deviceCategory:id,name,user_id',
            'deviceStatus:id,name'
        )->firstOrFail();
    }
}
