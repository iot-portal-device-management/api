<?php

namespace App\Actions\DeviceGroup;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableDeviceGroupsAction extends FilterDataTableAction
{
    protected array|null $quickFilterableColumns = [
        'id',
        'name',
    ];

    public function execute(array $data): LengthAwarePaginator
    {
        $this->query = DeviceGroup::userId($data['userId']);

        return $this->setData($data)->applySort()->applyFilters()->paginate();
    }
}
