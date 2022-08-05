<?php

namespace App\Actions\DeviceCategory;

use App\Models\Device;

class UpdateDeviceCategoryAction
{
    private FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction;

    public function __construct(FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction)
    {
        $this->findDeviceCategoryByIdAction = $findDeviceCategoryByIdAction;
    }

    public function execute(string $deviceCategoryId, array $data): bool
    {
        $deviceCategory = $this->findDeviceCategoryByIdAction->execute($deviceCategoryId);

        if (isset($data['name'])) {
            $deviceCategory->update([
                'name' => $data['name'],
            ]);
        }

        if (isset($data['deviceIds']) && $data['deviceIds']) {
            Device::idIn($data['deviceIds'])->update([
                'device_category_id' => $deviceCategory->id,
            ]);
        }

        return true;
    }
}
