<?php

namespace App\Actions\DeviceGroups;

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

        $deviceGroup->devices()->sync($data['deviceIds']);

        return true;
    }
}
