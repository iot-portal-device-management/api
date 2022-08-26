<?php

namespace App\Actions\DeviceGroup;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceGroup;
use Illuminate\Support\Facades\App;

class FilterDataTableDeviceGroupsAction
{
    private array|null $quickFilterableColumns = [
        'id',
        'name',
    ];

    public function execute(array $data)
    {
        $query = DeviceGroup::userId($data['userId']);

        $filterDataTableAction = App::makeWith(FilterDataTableAction::class, [
            'query' => $query,
            'quickFilterableColumns' => $this->quickFilterableColumns,
            'sortModel' => $data['sortModel'] ?? null,
            'filterModel' => $data['filterModel'] ?? null,
        ]);

        return $filterDataTableAction->applySort()->applyFilters()->paginate($data['pageSize']);
    }
}
