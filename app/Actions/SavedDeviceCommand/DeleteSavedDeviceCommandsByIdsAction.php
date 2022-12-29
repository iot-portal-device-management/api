<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\SavedDeviceCommand;

use App\Models\SavedDeviceCommand;

class DeleteSavedDeviceCommandsByIdsAction
{
    public function execute(array $ids): bool
    {
        return SavedDeviceCommand::idIn($ids)->delete();
    }
}
