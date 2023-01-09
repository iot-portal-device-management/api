<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCategory;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableDeviceCategoryDevicesAction extends FilterDataTableAction
{
    protected array|null $quickFilterableColumns = [
        'id',
        'name',
        'bios_vendor',
        'bios_version',
        'deviceCategory:name',
        'deviceStatus:name',
    ];

    public function execute(array $data): LengthAwarePaginator
    {
        $this->query = Device::joinRelationship('deviceCategory', function ($join) use ($data) {
            $join->id($data['deviceCategoryId']);
        })->with(
            'deviceCategory:id,name',
            'deviceStatus:id,name',
        );

        return $this->setData($data)->applySort()->applyFilters()->paginate();
    }
}
