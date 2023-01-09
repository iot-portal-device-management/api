<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DataTable;

class CalculateDataTableFinalPageSizeAction
{
    public function execute(?int $pageSize): int
    {
        $defaultPageSize = config('datatable.default_page_size');

        $maxPageSize = config('datatable.max_page_size');

        return min($pageSize ?? $defaultPageSize, $maxPageSize);
    }
}
