<?php

namespace App\Actions\SavedDeviceCommand;

use App\Models\SavedDeviceCommand;
use App\Models\User;

class CreateSavedDeviceCommandAction
{
    public function execute(User $user, array $data): SavedDeviceCommand
    {
        return SavedDeviceCommand::create([
            'name' => $data['name'],
            'device_command_type_name' => $data['deviceCommandTypeName'],
            'payload' => is_null($data['payload']) ? null : json_encode($data['payload']),
            'user_id' => $user->id,
        ]);
    }
}
