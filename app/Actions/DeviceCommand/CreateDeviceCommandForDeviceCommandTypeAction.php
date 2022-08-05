<?php

namespace App\Actions\DeviceCommand;

use App\Models\DeviceCommand;

class CreateDeviceCommandForDeviceCommandTypeAction
{
    public function execute(array $data): DeviceCommand
    {
        return DeviceCommand::create([
            'payload' => isset($data['payload']) ? ($data['payload'] === 'null' ? null : $data['payload']) : null,
            'error' => $data['error'] ?? null,
            'started_at' => $data['started_at'] ?? null,
            'completed_at' => $data['completed_at'] ?? null,
            'responded_at' => $data['responded_at'] ?? null,
            'device_command_type_id' => $data['device_command_type_id'] ?? null,
            'device_job_id' => $data['device_job_id'] ?? null,
        ]);
    }
}
