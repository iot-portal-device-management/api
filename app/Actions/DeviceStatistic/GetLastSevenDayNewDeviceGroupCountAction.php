<?php

namespace App\Actions\DeviceStatistic;

use App\Models\DeviceGroup;
use App\Traits\PadStatisticWithEmptyRecords;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetLastSevenDayNewDeviceGroupCountAction
{
    use PadStatisticWithEmptyRecords;

    public function execute(): array
    {
        $deviceGroupTableName = DeviceGroup::getTableName();

        $newDeviceGroupCountByDate = Auth::user()->deviceGroups()
            ->where($deviceGroupTableName . '.created_at', '>=', Carbon::today()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(' . $deviceGroupTableName . '.created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->keyBy('date');

        return $this->transform($this->padEmptyRecordsWithZeroCount($newDeviceGroupCountByDate));
    }
}
