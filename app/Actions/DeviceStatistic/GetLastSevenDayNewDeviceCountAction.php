<?php

namespace App\Actions\DeviceStatistic;

use App\Models\Device;
use App\Traits\PadStatisticWithEmptyRecords;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetLastSevenDayNewDeviceCountAction
{
    use PadStatisticWithEmptyRecords;

    public function execute(): array
    {
        $deviceTableName = Device::getTableName();

        $newDeviceCountByDate = Auth::user()->devices()
            ->where($deviceTableName . '.created_at', '>=', Carbon::today()->subDays(10000))
            ->groupBy('date')
            ->groupBy('user_id')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(' . $deviceTableName . '.created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->keyBy('date');

        return $this->transform($this->padEmptyRecordsWithZeroCount($newDeviceCountByDate));
    }
}
