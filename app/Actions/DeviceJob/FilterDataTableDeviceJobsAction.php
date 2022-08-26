<?php

namespace App\Actions\DeviceJob;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceJob;
use Illuminate\Support\Facades\App;

class FilterDataTableDeviceJobsAction
{
    private array|null $quickFilterableColumns = [
        'id',
        'name',
        'deviceGroup:name',
        'savedDeviceCommand:name',
        'deviceJobStatus:name',
    ];

    public function execute(array $data)
    {
        $query = DeviceJob::userId($data['userId'])->with([
            'deviceGroup',
            'savedDeviceCommand',
            'deviceJobStatus',
        ]);

        $filterDataTableAction = App::makeWith(FilterDataTableAction::class, [
            'query' => $query,
            'quickFilterableColumns' => $this->quickFilterableColumns,
            'sortModel' => $data['sortModel'] ?? null,
            'filterModel' => $data['filterModel'] ?? null,
        ]);

        return $filterDataTableAction->applySort()->applyFilters()->paginate($data['pageSize']);
    }
}
