<?php

namespace App\Actions\DeviceJob;

use App\Models\DeviceJob;
use App\Models\User;

class CreateDeviceJobAction
{
    public function execute(User $user, array $data): DeviceJob
    {
        return DeviceJob::create([
            'name' => $data['name'],
            'user_id' => $user->id,
            'device_group_id' => $data['deviceGroupId'],
            'saved_device_command_id' => $data['savedDeviceCommandId'],
        ]);
    }
}
