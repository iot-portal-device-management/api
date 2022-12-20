<?php

namespace App\Actions\SavedDeviceCommand;

use App\Models\SavedDeviceCommand;

class FindSavedDeviceCommandByIdAction
{
    public function execute(string $id): SavedDeviceCommand
    {
        return SavedDeviceCommand::findOrFail($id);
    }
}
