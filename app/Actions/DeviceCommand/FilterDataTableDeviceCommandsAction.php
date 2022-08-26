<?php

namespace App\Actions\DeviceCommand;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceCommand;
use Illuminate\Support\Facades\App;

class FilterDataTableDeviceCommandsAction
{
    private array|null $quickFilterableColumns = [
        'id',
        'payload',
        'deviceCommandType:name',
    ];

    public function execute(array $data)
    {
        $query = DeviceCommand::joinRelationship('deviceCommandType.device', [
            'device' => function ($join) use ($data) {
                $join->id($data['deviceId']);
            },
        ])->with(
            'deviceCommandType:id,name',
        );

        $filterDataTableAction = App::makeWith(FilterDataTableAction::class, [
            'query' => $query,
            'quickFilterableColumns' => $this->quickFilterableColumns,
            'sortModel' => $data['sortModel'] ?? null,
            'filterModel' => $data['filterModel'] ?? null,
        ]);

        return $filterDataTableAction->applySort()->applyFilters()->paginate($data['pageSize']);
    }
}
