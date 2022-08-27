<?php

namespace App\Actions\DeviceJob;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceCommand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableDeviceJobDeviceCommandsAction extends FilterDataTableAction
{
    protected array|null $quickFilterableColumns = [
        'id',
        'deviceCommandType.device:name',
        'deviceCommandType.device.deviceCategory:name',
        'deviceCommandType.device.deviceStatus:name',
        'deviceCommandStatus:name',
    ];

    public function execute(array $data): LengthAwarePaginator
    {
        $this->query = DeviceCommand::deviceJobId($data['deviceJobId'])->with(
            'deviceCommandStatus',
            'deviceCommandType.device.deviceCategory',
            'deviceCommandType.device.deviceStatus'
        );

        return $this->setData($data)->applySort()->applyFilters()->paginate();
    }
}
