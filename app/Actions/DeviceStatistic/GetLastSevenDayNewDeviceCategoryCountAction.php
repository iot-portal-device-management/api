<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceStatistic;

use App\Models\DeviceCategory;
use App\Traits\PadStatisticWithEmptyRecords;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetLastSevenDayNewDeviceCategoryCountAction
{
    use PadStatisticWithEmptyRecords;

    public function execute(): array
    {
        $deviceCategoryTableName = DeviceCategory::getTableName();

        $newDeviceCategoryCountByDate = Auth::user()->deviceCategories()
            ->where($deviceCategoryTableName . '.created_at', '>=', Carbon::today()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(' . $deviceCategoryTableName . '.created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->keyBy('date');

        return $this->transform($this->padEmptyRecordsWithZeroCount($newDeviceCategoryCountByDate));
    }
}
