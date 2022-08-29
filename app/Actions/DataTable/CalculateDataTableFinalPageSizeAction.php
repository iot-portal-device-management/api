<?php

namespace App\Actions\DataTable;

use Illuminate\Support\Facades\Config;

class CalculateDataTableFinalPageSizeAction
{
    public function execute(?int $pageSize): int
    {
        $defaultPageSize = Config::get('datatable.default_page_size');

        $maxPageSize = Config::get('datatable.max_page_size');

        return min($pageSize ?? $defaultPageSize, $maxPageSize);
    }
}
