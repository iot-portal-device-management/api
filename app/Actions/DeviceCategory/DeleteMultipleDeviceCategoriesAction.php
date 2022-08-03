<?php

namespace App\Actions\DeviceCategory;

use App\Models\DeviceCategory;

class DeleteMultipleDeviceCategoriesAction
{
    public function execute(array $ids): bool
    {
        return DeviceCategory::idIn($ids)->delete();
    }
}
