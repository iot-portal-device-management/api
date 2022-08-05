<?php

namespace App\Actions\DeviceCommand;

use App\Models\DeviceCommand;

class MarkDeviceCommandAsCompletedAction
{
    public function execute(DeviceCommand $deviceCommand): bool
    {
        return $deviceCommand->update([
            'completed_at' => now(),
            'responded_at' => now(),
        ]);
    }
}
