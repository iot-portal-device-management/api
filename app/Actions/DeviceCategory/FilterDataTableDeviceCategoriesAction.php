<?php

namespace App\Actions\DeviceCategory;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceCategory;
use Illuminate\Support\Facades\App;

class FilterDataTableDeviceCategoriesAction
{
    private array|null $quickFilterableColumns = [
        'id',
        'name',
    ];

    public function execute(array $data)
    {
        $query = DeviceCategory::userId($data['userId']);

        $filterDataTableAction = App::makeWith(FilterDataTableAction::class, [
            'query' => $query,
            'quickFilterableColumns' => $this->quickFilterableColumns,
            'sortModel' => $data['sortModel'] ?? null,
            'filterModel' => $data['filterModel'] ?? null,
        ]);

        return $filterDataTableAction->applySort()->applyFilters()->paginate($data['pageSize'], ['id', 'name']);
    }
}
