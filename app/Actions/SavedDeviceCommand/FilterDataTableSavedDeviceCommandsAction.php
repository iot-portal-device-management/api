<?php

namespace App\Actions\SavedDeviceCommand;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\SavedDeviceCommand;
use Illuminate\Support\Facades\App;

class FilterDataTableSavedDeviceCommandsAction
{
    //TODO: convert to use inheritance
    private array|null $quickFilterableColumns = [
        'id',
        'name',
    ];

    public function execute(array $data)
    {
        $query = SavedDeviceCommand::userId($data['userId']);

        $filterDataTableAction = App::makeWith(FilterDataTableAction::class, [
            'query' => $query,
            'quickFilterableColumns' => $this->quickFilterableColumns,
            'sortModel' => $data['sortModel'] ?? null,
            'filterModel' => $data['filterModel'] ?? null,
        ]);

        //TODO: implement paginate all rows shortcut
        return $filterDataTableAction->applySort()->applyFilters()->paginate($data['pageSize']);
    }
}
