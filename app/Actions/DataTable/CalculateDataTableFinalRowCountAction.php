<?php

namespace App\Actions\DataTable;

use Illuminate\Support\Facades\Config;

class CalculateDataTableFinalRowCountAction
{
    public function execute(?int $rows)
    {
        $maxRows = Config::get('constants.index_max_rows');

        return min($rows ?? 10, $maxRows);
    }
}
