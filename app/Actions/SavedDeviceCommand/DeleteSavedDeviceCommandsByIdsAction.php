<?php

namespace App\Actions\SavedDeviceCommand;

use App\Models\SavedDeviceCommand;

class DeleteSavedDeviceCommandsByIdsAction
{
    public function execute(array $ids): bool
    {
        return SavedDeviceCommand::idIn($ids)->delete();
    }
}
