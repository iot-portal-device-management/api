<?php

namespace App\Actions\DeviceCategories;

use App\Models\DeviceCategory;

class FindDeviceCategoryByIdAction
{
    public function execute(string $id): DeviceCategory
    {
        return DeviceCategory::findOrFail($id);
    }
}
