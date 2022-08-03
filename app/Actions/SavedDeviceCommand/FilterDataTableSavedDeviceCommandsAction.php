<?php

namespace App\Actions\SavedDeviceCommand;

use App\Actions\DataTable\CalculateDataTableFinalRowCountAction;
use Illuminate\Support\Facades\Auth;

class FilterDataTableSavedDeviceCommandsAction
{
    private CalculateDataTableFinalRowCountAction $calculateDataTableFinalRowCountAction;

    public function __construct(CalculateDataTableFinalRowCountAction $calculateDataTableFinalRowCountAction)
    {
        $this->calculateDataTableFinalRowCountAction = $calculateDataTableFinalRowCountAction;
    }

    public function execute(array $data)
    {
        $query = Auth::user()->savedCommands();

        if (isset($data['filters'])) {
            $filters = json_decode($data['filters']);

            foreach ($filters as $key => $value) {
                if ($key === 'unique_id') $query->uniqueIdLike($value->value);

                if ($key === 'name') $query->nameLike($value->value);

                if ($key === 'command_name') $query->commandNameLike($value->value);

                if ($key === 'globalFilter') {
                    $query->where(function ($query) use ($value) {
                        $query->uniqueIdLike($value->value)
                            ->orWhere->nameLike($value->value)
                            ->orWhere->commandNameLike($value->value);
                    });
                }
            }
        }

        if (isset($data['sortField'])) {
            if ($data['sortOrder'] === '1')
                $query->orderBy($data['sortField']);
            else
                $query->orderByDesc($data['sortField']);
        }

        $rows = $this->calculateDataTableFinalRowCountAction->execute($data['rows'] ?? null);

        return $query->paginate($rows);
    }
}
