<?php

namespace App\Actions\DeviceCategory;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableDeviceCategoriesAction extends FilterDataTableAction
{
    protected array|null $quickFilterableColumns = [
        'id',
        'name',
    ];

    public function execute(array $data): LengthAwarePaginator
    {
        $this->query = DeviceCategory::userId($data['userId']);

        return $this->setData($data)->applySort()->applyFilters()->paginate(null, ['id', 'name']);
    }
}
