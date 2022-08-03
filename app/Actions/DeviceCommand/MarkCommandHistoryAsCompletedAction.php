<?php

namespace App\Actions\DeviceCommand;

use App\Models\DeviceCommand;

class MarkCommandHistoryAsCompletedAction
{
    public function execute(DeviceCommand $commandHistory): bool
    {
        return $commandHistory->update([
            'completed_at' => now(),
            'responded_at' => now(),
        ]);
    }
}
