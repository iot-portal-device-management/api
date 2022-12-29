<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceGroup;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableDeviceGroupDevicesAction extends FilterDataTableAction
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
        $this->query = Device::joinRelationship('deviceGroups', [
            'device_groups' => function ($join) use ($data) {
                $join->id($data['deviceGroupId']);
            },
        ])->with(
            'deviceCategory:id,name',
            'deviceStatus:id,name',
        );

        return $this->setData($data)->applySort()->applyFilters()->paginate();
    }
}
