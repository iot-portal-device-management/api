<?php

namespace App\Actions\CommandHistories;

use App\Actions\DataTables\CalculateDataTableFinalRowsAction;
use App\Http\Resources\CommandHistoryCollectionPagination;
use App\Models\CommandHistory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;

class FilterDataTableCommandHistoriesAction
{
    private CalculateDataTableFinalRowsAction $calculateDataTableFinalRowsAction;

    public function __construct(CalculateDataTableFinalRowsAction $calculateDataTableFinalRowsAction)
    {
        $this->calculateDataTableFinalRowsAction = $calculateDataTableFinalRowsAction;
    }

    public function execute(string $deviceId, array $data)
    {
        $query = CommandHistory::deviceId($deviceId)->with('command:id,name');

        $relations = ['command'];

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
                                ->whereColumn($modelQualifiedName::getTableName() . '.id', CommandHistory::getTableName() . '.' . Str::snake($relation) . '_id')
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
                            $query->where(CommandHistory::getTableName() . '.' . Str::snake($filterItem->columnField), 'ILIKE', "%{$filterItem->value}%");
                        }
                    }
                }
            }

            if (isset($filterOptions->quickFilterValues)) {
                $quickFilterColumns = ['payload', 'command:name'];

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
                                    $query->orWhere->where(CommandHistory::getTableName() . '.' . $quickFilterColumn, 'ILIKE', "%{$quickFilterValue}%");
                                }
                            }
                        });
                    }
                }
            }
        }

        $pageSize = $this->calculateDataTableFinalRowsAction->execute($data['pageSize'] ?? null);

        return new CommandHistoryCollectionPagination($query->paginate($pageSize));
    }
}
