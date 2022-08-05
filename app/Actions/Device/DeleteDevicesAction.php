<?php

namespace App\Actions\Device;

use App\Models\Device;

class DeleteDevicesAction
{
    public function execute(array $ids): bool
    {
        return Device::idIn($ids)->delete();
    }
}
