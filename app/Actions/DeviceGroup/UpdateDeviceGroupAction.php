<?php

namespace App\Actions\DeviceGroup;

use App\Models\DeviceGroup;

class UpdateDeviceGroupAction
{
    private FindDeviceGroupByIdAction $findDeviceGroupByIdAction;

    public function __construct(FindDeviceGroupByIdAction $findDeviceGroupByIdAction)
    {
        $this->findDeviceGroupByIdAction = $findDeviceGroupByIdAction;
    }

    public function execute(string $id, array $data): bool
    {
        $deviceGroup = $this->findDeviceGroupByIdAction->execute($id);

        if (isset($data['name'])) {
            $deviceGroup->update([
                'name' => $data['name'],
            ]);
        }

        if (isset($data['deviceIds']) && $data['deviceIds']) {
            $deviceRows = [];

            foreach ($data['deviceIds'] as $deviceId) {
                $deviceRows[$deviceId] = ['id' => DeviceGroup::generateId()];
            }

            $deviceGroup->devices()->sync($deviceRows);
        }

        return true;
    }
}
