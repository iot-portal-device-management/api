<?php

namespace App\Actions\DeviceJob;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceJob;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableDeviceJobsAction extends FilterDataTableAction
{
    protected array|null $quickFilterableColumns = [
        'id',
        'name',
        'deviceGroup:name',
        'savedDeviceCommand:name',
        'deviceJobStatus:name',
    ];

    public function execute(array $data): LengthAwarePaginator
    {
        $this->query = DeviceJob::userId($data['userId'])->with([
            'deviceGroup',
            'savedDeviceCommand',
            'deviceJobStatus',
        ]);

        return $this->setData($data)->applySort()->applyFilters()->paginate();
    }
}
