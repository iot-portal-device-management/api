<?php

namespace App\Actions\Device;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\Device;
use Illuminate\Support\Facades\App;

class FilterDataTableDevicesAction
{
    private array|null $quickFilterableColumns = [
        'id',
        'name',
        'bios_vendor',
        'bios_version',
        'deviceCategory:name',
        'deviceStatus:name',
    ];

    public function execute(array $data)
    {
        $query = Device::joinRelationship('deviceCategory.user', [
            'user' => function ($join) use ($data) {
                $join->id($data['userId']);
            },
        ])->with(
            'deviceCategory:id,name',
            'deviceStatus:id,name',
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
