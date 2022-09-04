<?php

namespace App\Actions\DeviceJob;

use App\Models\DeviceJob;

class FindDeviceJobByIdAction
{
    public function execute(string $id): DeviceJob
    {
        return DeviceJob::id($id)->with('deviceJobStatus:id,name')->firstOrFail();

//        return DeviceJob::id($id)->with(
//            'deviceGroup',
//            'savedDeviceCommand',
//            'deviceGroup.devices.deviceCategory',
//            'deviceGroup.devices.deviceStatus',
//            'deviceCommands.deviceCommandStatus',
//            'deviceCommands.deviceCommandType:id,device_id'
//        )->firstOrFail();
    }
}
