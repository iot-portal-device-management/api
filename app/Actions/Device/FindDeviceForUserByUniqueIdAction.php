<?php

namespace App\Actions\Device;

use App\Models\Device;
use App\Models\User;

class FindDeviceForUserByUniqueIdAction
{
    public function execute(User $user, string $uniqueId): Device
    {
        return $user->devices()->uniqueId($uniqueId)->firstOrFail();
    }
}
