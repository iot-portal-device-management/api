<?php

namespace App\Actions\SavedDeviceCommand;

use App\Models\SavedDeviceCommand;

class DeleteSavedDeviceCommandsAction
{
    public function execute(array $ids): bool
    {
        return SavedDeviceCommand::idIn($ids)->delete();
    }
}
