<?php

namespace App\Actions\DeviceJob;

use App\Actions\DataTable\FilterDataTableAction;
use App\Http\Resources\DeviceCommandCollectionPagination;
use App\Models\DeviceCommand;
use Illuminate\Support\Facades\App;

class FilterDataTableDeviceJobDeviceCommandsAction
{
    private array|null $quickFilterableColumns = [
        'id',
        'deviceCommandType.device:name',
        'deviceCommandType.device.deviceCategory:name',
        'deviceCommandType.device.deviceStatus:name',
        'deviceCommandStatus:name',
    ];

    public function execute(string $deviceJobId, array $data)
    {
        $query = DeviceCommand::deviceJobId($deviceJobId)->with(
            'deviceCommandStatus',
            'deviceCommandType.device.deviceCategory',
            'deviceCommandType.device.deviceStatus'
        );

        $filterDataTableAction = App::makeWith(FilterDataTableAction::class, [
            'query' => $query,
            'quickFilterableColumns' => $this->quickFilterableColumns,
            'sortModel' => $data['sortModel'] ?? null,
            'filterModel' => $data['filterModel'] ?? null,
        ]);

        return new DeviceCommandCollectionPagination($filterDataTableAction->applySort()->applyFilters()->paginate());
    }
}
