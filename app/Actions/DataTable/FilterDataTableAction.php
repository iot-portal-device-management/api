<?php

namespace App\Actions\DataTable;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Str;

class FilterDataTableAction
{
    private CalculateDataTableFinalRowCountAction $calculateDataTableFinalRowCountAction;

    private EloquentBuilder $query;

    private int|null $pageSize;

    private array|null $quickFilterableColumns;

    private array|null $sortModel;

    private array|null $filterModel;

    public function __construct(
        CalculateDataTableFinalRowCountAction $calculateDataTableFinalRowCountAction,
        EloquentBuilder $query,
        int $pageSize = null,
        array $quickFilterableColumns = null,
        array $sortModel = null,
        array|string $filterModel = null
    )
    {
        $this->calculateDataTableFinalRowCountAction = $calculateDataTableFinalRowCountAction;
        $this->query = $query;
        $this->pageSize = $pageSize;
        $this->quickFilterableColumns = $quickFilterableColumns;
        $this->sortModel = $sortModel ? $this->decodeSortModel($sortModel) : $sortModel;
        $this->filterModel = $filterModel ? $this->decodeFilterModel($filterModel) : $filterModel;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function decodeSortModel(array|string|null $sortModel): array|null
    {
        if (is_string($sortModel)) {
            return json_decode($sortModel, true);
        }

        return $sortModel;
    }

    public function decodeFilterModel(array|string|null $filterModel): array|null
    {
        if (is_string($filterModel)) {
            return json_decode($filterModel, true);
        }

        return $filterModel;
    }

    public function findLatestRelation(string $relations)
    {
        $relationNames = explode('.', $relations);

        $latestRelation = null;

        foreach ($relationNames as $relationName) {
            $currentModel = $latestRelation ? $latestRelation->getModel() : $this->query->getModel();
            $relation = $currentModel->{$relationName}();
            $latestRelation = $relation;
        }

        return $latestRelation;
    }

    public function applyFieldFilters(array $filters)
    {
        if (isset($filters)) {
            foreach ($filters as $filter) {
                if (isset($filter['value']) && $filter['value'] !== '') {
                    if (Str::contains($filter['columnField'], '.')) {
                        $relationNames = explode('.', $filter['columnField']);
                        $column = array_pop($relationNames);
                        $relationName = implode('.', $relationNames);

                        $latestRelationModel = $this->findLatestRelation($relationName)->getModel();

                        if ($latestRelationModel::isColumnFilterable(Str::snake($column))) {
                            $this->query->whereHas($relationName, function (EloquentBuilder $query) use ($latestRelationModel, $column, $filter) {
                                $query->where($latestRelationModel->qualifyColumn(Str::snake($column)), 'ILIKE', "%{$filter['value']}%");
                            });
                        }
                    } else {
                        $currentModel = $this->query->getModel();

                        if ($currentModel::isColumnFilterable(Str::snake($filter['columnField']))) {
                            $this->query->where($currentModel->qualifyColumn(Str::snake($filter['columnField'])), 'ILIKE', "%{$filter['value']}%");
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function applyQuickFilters(array $quickFilterValues)
    {
        if ($this->quickFilterableColumns && $quickFilterValues) {
            $quickFilterableColumns = $this->quickFilterableColumns;

            foreach ($quickFilterValues as $quickFilterValue) {
                if (isset($quickFilterValue)) {
                    $this->query->where(function (EloquentBuilder $query) use ($quickFilterableColumns, $quickFilterValue) {
                        foreach ($this->quickFilterableColumns as $quickFilterableColumn) {
                            if (Str::contains($quickFilterableColumn, ':')) {
                                $relationNameAndColumnString = explode(':', $quickFilterableColumn);
                                $relationName = $relationNameAndColumnString[0];
                                $columns = explode(',', $relationNameAndColumnString[1]);

                                $latestRelationModel = $this->findLatestRelation($relationName)->getModel();

                                foreach ($columns as $column) {
                                    $query->orWhere->whereHas($relationName, function (EloquentBuilder $query) use ($latestRelationModel, $column, $quickFilterValue) {
                                        $query->where($latestRelationModel->qualifyColumn(Str::snake($column)), 'ILIKE', "%{$quickFilterValue}%");
                                    });
                                }
                            } else {
                                $query->orWhere->where($query->getModel()->qualifyColumn(Str::snake($quickFilterableColumn)), 'ILIKE', "%{$quickFilterValue}%");
                            }
                        }
                    });
                }
            }
        }

        return $this;
    }

    public function applySort()
    {
        if (isset($this->sortModel)) {
            foreach ($this->sortModel as $sortCriterion) {
                $sort = json_decode($sortCriterion, true);

                if (Str::contains($sort['field'], '.')) {
                    $relationNames = explode('.', $sort['field']);
                    $column = array_pop($relationNames);
                    $relationName = implode('.', $relationNames);
                    $latestRelation = $this->findLatestRelation($relationName);

                    if ($latestRelation->getModel()::isColumnSortable(Str::snake($column))) {
                        $this->query->orderByLeftPowerJoins($relationName . '.' . Str::snake($column), $sort['sort']);
                    }
                } else {
                    $currentModel = $this->query->getModel();

                    if ($currentModel::isColumnSortable(Str::snake($sort['field']))) {
                        $this->query->orderBy($currentModel->qualifyColumn(Str::snake($sort['field'])), $sort['sort']);
                    }
                }
            }
        }

        return $this;
    }

    public function applyFilters()
    {
        if (isset($this->filterModel)) {
            if (isset($this->filterModel['items'])) {
                $this->applyFieldFilters($this->filterModel['items']);
            }

            if (isset($this->filterModel['quickFilterValues'])) {
                $this->applyQuickFilters($this->filterModel['quickFilterValues']);
            }
        }

        return $this;
    }

    public function paginate(): LengthAwarePaginator
    {
        $pageSize = $this->calculateDataTableFinalRowCountAction->execute($this->pageSize);

        return $this->query->paginate($pageSize);
    }
}
