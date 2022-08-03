<?php

namespace App\Actions\DeviceCommand;

use App\Models\DeviceCommandType;
use App\Models\DeviceCommand;
use Illuminate\Database\Eloquent\Model;

class CreateDeviceCommandForDeviceCommandTypeAction
{
    public function execute(DeviceCommandType $deviceCommandType, array $data): DeviceCommand|Model
    {
        return $deviceCommandType->deviceCommands()->create([
            'payload' => isset($data['payload']) ? ($data['payload'] === 'null' ? null : $data['payload']) : null,
            'error' => $data['error'] ?? null,
            'started_at' => $data['started_at'] ?? null,
            'completed_at' => $data['completed_at'] ?? null,
            'responded_at' => $data['responded_at'] ?? null,
            'device_job_id' => $data['device_job_id'] ?? null,
        ]);
    }
}
