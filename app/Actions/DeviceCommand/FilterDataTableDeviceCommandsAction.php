<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCommand;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\DeviceCommand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableDeviceCommandsAction extends FilterDataTableAction
{
    protected array|null $quickFilterableColumns = [
        'id',
        'payload',
        'deviceCommandType:name',
    ];

    public function execute(array $data): LengthAwarePaginator
    {
        $this->query = DeviceCommand::joinRelationship('deviceCommandType.device', [
            'device' => function ($join) use ($data) {
                $join->id($data['deviceId']);
            },
        ])->with(
            'deviceCommandType:id,name',
        );

        return $this->setData($data)->applySort()->applyFilters()->paginate();
    }
}
