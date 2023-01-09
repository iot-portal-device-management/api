<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\SavedDeviceCommand;

use App\Actions\DataTable\FilterDataTableAction;
use App\Models\SavedDeviceCommand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterDataTableSavedDeviceCommandsAction extends FilterDataTableAction
{
    protected array|null $quickFilterableColumns = [
        'id',
        'name',
        'device_command_type_name',
        'payload',
    ];

    public function execute(array $data): LengthAwarePaginator
    {
        $this->query = SavedDeviceCommand::userId($data['userId']);

        return $this->setData($data)->applySort()->applyFilters()->paginate();
    }
}
