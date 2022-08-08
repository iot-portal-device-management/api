<?php

namespace App\Actions\SavedDeviceCommand;

use App\Actions\DataTable\CalculateDataTableFinalRowCountAction;
use App\Http\Resources\SavedDeviceCommandCollectionPagination;
use App\Models\SavedDeviceCommand;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FilterDataTableSavedDeviceCommandsAction
{
    private CalculateDataTableFinalRowCountAction $calculateDataTableFinalRowCountAction;

    public function __construct(CalculateDataTableFinalRowCountAction $calculateDataTableFinalRowCountAction)
    {
        $this->calculateDataTableFinalRowCountAction = $calculateDataTableFinalRowCountAction;
    }

    public function execute(array $data)
    {
        $query = Auth::user()->savedDeviceCommands();

        $relations = [];

        if (isset($data['sortModel'])) {
            foreach ($data['sortModel'] as $sortCriterion) {
                $sortCriterionObject = json_decode($sortCriterion);

                if (Str::contains($sortCriterionObject->field, '.')) {
                    $relationAndColumn = explode('.', $sortCriterionObject->field);
                    $relation = $relationAndColumn[0];
                    $column = $relationAndColumn[1];

                    if (in_array($relation, $relations)) {
                        $query->orderBy(function (QueryBuilder $query) use ($relation, $column) {
                            $modelQualifiedName = 'App\Models\\' . Str::studly($relation);

                            $query->select(Str::snake($column))
                                ->from($modelQualifiedName::getTableName())
                                ->whereColumn($modelQualifiedName::getTableName() . '.id', SavedDeviceCommand::getTableName() . '.' . Str::snake($relation) . '_id')
                                ->limit(1);
                        }, $sortCriterionObject->sort);
                    }
                } else {
                    $snakeCaseField = Str::snake($sortCriterionObject->field);
                    $query->orderBy($snakeCaseField, $sortCriterionObject->sort);
                }
            }
        }

        if (isset($data['filterModel'])) {
            $filterOptions = json_decode($data['filterModel']);

            if (isset($filterOptions->items)) {
                foreach ($filterOptions->items as $filterItem) {
                    if (isset($filterItem->value) && $filterItem->value !== '') {
                        if (Str::contains($filterItem->columnField, '.')) {
                            $relationAndColumn = explode('.', $filterItem->columnField);

                            $query->whereHas(Str::camel($relationAndColumn[0]), function (EloquentBuilder $query) use ($relationAndColumn, $filterItem) {
                                $modelQualifiedName = 'App\Models\\' . Str::studly($relationAndColumn[0]);
                                $query->where($modelQualifiedName::getTableName() . '.' . Str::snake($relationAndColumn[1]), 'ILIKE', "%{$filterItem->value}%");
                            });
                        } else {
                            $query->where(SavedDeviceCommand::getTableName() . '.' . Str::snake($filterItem->columnField), 'ILIKE', "%{$filterItem->value}%");
                        }
                    }
                }
            }

            if (isset($filterOptions->quickFilterValues)) {
                $quickFilterColumns = ['id', 'name'];

                foreach ($filterOptions->quickFilterValues as $quickFilterValue) {
                    if (isset($quickFilterValue)) {
                        $query->where(function (EloquentBuilder $query) use ($quickFilterColumns, $quickFilterValue) {
                            foreach ($quickFilterColumns as $quickFilterColumn) {
                                if (Str::contains($quickFilterColumn, ':')) {
                                    $relationAndColumnsString = explode(':', $quickFilterColumn);
                                    $relation = $relationAndColumnsString[0];
                                    $columns = explode(',', $relationAndColumnsString[1]);

                                    foreach ($columns as $column) {
                                        $query->orWhere->whereHas($relation, function (EloquentBuilder $query) use ($quickFilterValue, $relation, $column) {
                                            $modelQualifiedName = 'App\Models\\' . Str::studly($relation);
                                            $query->where($modelQualifiedName::getTableName() . '.' . $column, 'ILIKE', "%{$quickFilterValue}%");
                                        });
                                    }
                                } else {
                                    $query->orWhere->where(SavedDeviceCommand::getTableName() . '.' . $quickFilterColumn, 'ILIKE', "%{$quickFilterValue}%");
                                }
                            }
                        });
                    }
                }
            }
        }

        $pageSize = $this->calculateDataTableFinalRowCountAction->execute($data['pageSize'] ?? null);

        return new SavedDeviceCommandCollectionPagination($query->paginate($pageSize));
    }
}
