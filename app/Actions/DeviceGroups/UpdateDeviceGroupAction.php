<?php

namespace App\Actions\DeviceGroups;

use App\Models\DeviceGroup;

class UpdateDeviceGroupAction
{
    private FindDeviceGroupByIdAction $findDeviceGroupByIdAction;

    public function __construct(FindDeviceGroupByIdAction $findDeviceGroupByIdAction)
    {
        $this->findDeviceGroupByIdAction = $findDeviceGroupByIdAction;
    }

    public function execute(string $deviceGroupId, array $data): bool
    {
        $deviceGroup = $this->findDeviceGroupByIdAction->execute($deviceGroupId);

        if ($data['name']) {
            $deviceGroup->update([
                'name' => $data['name'],
            ]);
        }

        $deviceRows = [];

        foreach ($data['deviceIds'] as $deviceId) {
            $deviceRows[$deviceId] = ['id' => DeviceGroup::generateId()];
        }

        $deviceGroup->devices()->sync($deviceRows);

        return true;
    }
}
