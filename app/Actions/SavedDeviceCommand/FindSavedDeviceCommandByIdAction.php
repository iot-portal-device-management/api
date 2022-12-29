<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\SavedDeviceCommand;

use App\Models\SavedDeviceCommand;

class FindSavedDeviceCommandByIdAction
{
    public function execute(string $id): SavedDeviceCommand
    {
        return SavedDeviceCommand::findOrFail($id);
    }
}
