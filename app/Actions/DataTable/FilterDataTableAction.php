<?php

namespace App\Actions\DataTable;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class FilterDataTableAction
{
    protected EloquentBuilder|Relation $query;

    protected array|null $quickFilterableColumns;

    protected array|null $data;

    protected array|null $sortModel;

    protected array|null $filterModel;

    public function setData(array $data = null): static
    {
        $this->data = $data;
        $this->sortModel = isset($data['sortModel']) ? $this->decodeSortModel($data['sortModel']) : null;
        $this->filterModel = isset($data['filterModel']) ? $this->decodeFilterModel($data['filterModel']) : null;

        return $this;
    }

    public function getQuery(): Relation|EloquentBuilder
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

    public function findLatestRelation(string $relations): Relation
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

    public function applyFieldFilters(array $filters): static
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
                            $this->query
                                ->whereHas($relationName,
                                    function (EloquentBuilder $query) use ($latestRelationModel, $column, $filter) {
                                        $query
                                            ->where(
                                                $latestRelationModel->qualifyColumn(Str::snake($column)),
                                                'ILIKE',
                                                "%{$filter['value']}%",
                                            );
                                    });
                        }
                    } else {
                        $currentModel = $this->query->getModel();

                        if ($currentModel::isColumnFilterable(Str::snake($filter['columnField']))) {
                            $this->query
                                ->where(
                                    $currentModel->qualifyColumn(Str::snake($filter['columnField'])),
                                    'ILIKE',
                                    "%{$filter['value']}%",
                                );
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function applyQuickFilters(array $quickFilterValues): static
    {
        if ($this->quickFilterableColumns && $quickFilterValues) {
            $quickFilterableColumns = $this->quickFilterableColumns;

            foreach ($quickFilterValues as $quickFilterValue) {
                if (isset($quickFilterValue)) {
                    $this->query
                        ->where(
                            function (EloquentBuilder $query) use ($quickFilterableColumns, $quickFilterValue) {
                                foreach ($this->quickFilterableColumns as $quickFilterableColumn) {
                                    if (Str::contains($quickFilterableColumn, ':')) {
                                        $relationNameAndColumnString = explode(':', $quickFilterableColumn);
                                        $relationName = $relationNameAndColumnString[0];
                                        $columns = explode(',', $relationNameAndColumnString[1]);

                                        $latestRelationModel = $this->findLatestRelation($relationName)->getModel();

                                        foreach ($columns as $column) {
                                            $query
                                                ->orWhere
                                                ->whereHas(
                                                    $relationName,
                                                    function (EloquentBuilder $query) use (
                                                        $latestRelationModel,
                                                        $column,
                                                        $quickFilterValue
                                                    ) {
                                                        $query->where(
                                                            $latestRelationModel->qualifyColumn(Str::snake($column)),
                                                            'ILIKE',
                                                            "%{$quickFilterValue}%",
                                                        );
                                                    });
                                        }
                                    } else {
                                        $query
                                            ->orWhere
                                            ->where(
                                                $query->getModel()->qualifyColumn(Str::snake($quickFilterableColumn)),
                                                'ILIKE',
                                                "%{$quickFilterValue}%",
                                            );
                                    }
                                }
                            });
                }
            }
        }

        return $this;
    }

    public function applySort(): static
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
                        $this->query
                            ->orderByPowerJoins(
                                $relationName . '.' . Str::snake($column),
                                $sort['sort'],
                            );
                    }
                } else {
                    $currentModel = $this->query->getModel();

                    if ($currentModel::isColumnSortable(Str::snake($sort['field']))) {
                        $this->query
                            ->orderBy(
                                $currentModel->qualifyColumn(Str::snake($sort['field'])),
                                $sort['sort'],
                            );
                    }
                }
            }
        }

        return $this;
    }

    public function applyFilters(): static
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

    public function paginate(
        int|null $perPage = null,
        array $columns = ['*'],
        string $pageName = 'page',
        int $page = null
    ): LengthAwarePaginator
    {
        $perPage = $perPage ?? (isset($this->data['fetchAll']) && $this->data['fetchAll'] === 'true')
                ? $this->query->count() : $this->data['pageSize'] ?? null;

        if ($perPage === 0) {
            $perPage = $this->query->count();
        }

        $pageSize = (new CalculateDataTableFinalPageSizeAction)->execute($perPage);

        return $this->query->paginate($pageSize, $columns, $pageName, $page);
    }
}
