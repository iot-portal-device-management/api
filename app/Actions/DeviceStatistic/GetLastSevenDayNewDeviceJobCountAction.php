<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceStatistic;

use App\Models\DeviceJob;
use App\Traits\PadStatisticWithEmptyRecords;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetLastSevenDayNewDeviceJobCountAction
{
    use PadStatisticWithEmptyRecords;

    public function execute(): array
    {
        $deviceJobTableName = DeviceJob::getTableName();

        $newDeviceJobCountByDate = Auth::user()->deviceJobs()
            ->where($deviceJobTableName . '.created_at', '>=', Carbon::today()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(' . $deviceJobTableName . '.created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->keyBy('date');

        return $this->transform($this->padEmptyRecordsWithZeroCount($newDeviceJobCountByDate));
    }
}
