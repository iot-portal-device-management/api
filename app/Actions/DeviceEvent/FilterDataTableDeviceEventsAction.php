<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceEvent;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceEvent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableDeviceEventsAction extends FilterDataTableAction
{
    protected array|null $quickFilterableColumns = [
        'id',
        'raw_data',
        'deviceEventType:name',
    ];

    public function execute(array $data): LengthAwarePaginator
    {
        $this->query = DeviceEvent::joinRelationship('deviceEventType.device', [
            'device' => function ($join) use ($data) {
                $join->id($data['deviceId']);
            },
        ])->with(
            'deviceEventType:id,name',
        );

        return $this->setData($data)->applySort()->applyFilters()->paginate();
    }
}
