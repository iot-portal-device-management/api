<?php

namespace App\Actions\SavedDeviceCommand;

use App\Models\SavedDeviceCommand;

class FindSavedCommandByIdOrUniqueIdAction
{
    public function execute(string $id): SavedDeviceCommand
    {
        $query = is_numeric($id) ? SavedDeviceCommand::id($id) : SavedDeviceCommand::uniqueId($id);

        return $query->firstOrFail();
    }
}
