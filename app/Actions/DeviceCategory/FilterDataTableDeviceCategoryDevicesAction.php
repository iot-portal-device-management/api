<?php

namespace App\Actions\DeviceCategory;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\Device;
use Illuminate\Support\Facades\App;

class FilterDataTableDeviceCategoryDevicesAction
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
        $query = Device::joinRelationship('deviceCategory', function ($join) use ($data) {
            $join->id($data['deviceCategoryId']);
        })->with(
            'deviceCategory:id,name',
            'deviceStatus:id,name',
        );

        $filterDataTableAction = App::makeWith(FilterDataTableAction::class, [
            'query' => $query,
            'quickFilterableColumns' => $this->quickFilterableColumns,
            'sortModel' => $data['sortModel'] ?? null,
            'filterModel' => $data['filterModel'] ?? null,
        ])->applySort()->applyFilters();

        if (isset($data['fetchAll']) && $data['fetchAll'] === 'true') {
            return $filterDataTableAction->paginate($filterDataTableAction->getQuery()->count());
        }

        return $filterDataTableAction->paginate($data['pageSize']);
    }
}
