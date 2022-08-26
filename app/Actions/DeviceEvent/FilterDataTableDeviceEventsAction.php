<?php

namespace App\Actions\DeviceEvent;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceEvent;
use Illuminate\Support\Facades\App;

class FilterDataTableDeviceEventsAction
{
    private array|null $quickFilterableColumns = [
        'id',
        'raw_data',
        'deviceEventType:name',
    ];

    public function execute(array $data)
    {
        $query = DeviceEvent::joinRelationship('deviceEventType.device', [
            'device' => function ($join) use ($data) {
                $join->id($data['deviceId']);
            },
        ])->with(
            'deviceEventType:id,name',
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
