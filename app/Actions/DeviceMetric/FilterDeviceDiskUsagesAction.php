<?php

namespace App\Actions\DeviceMetric;

use App\Models\DeviceDiskStatistic;
use Illuminate\Database\Eloquent\Collection;

class FilterDeviceDiskUsagesAction
{
    public function execute(array $data): Collection
    {
        $timeRangeFilter = (int)($data['timeRangeFilter'] ?? 1);

        $diskUsages = DeviceDiskStatistic::deviceId($data['deviceId'])
            ->whereBetween('created_at', [now()->subHours($timeRangeFilter), now()])
            ->orderBy('created_at')
            ->get(['id', 'disk_usage_percentage', 'created_at']);

        return $this->transform($diskUsages);
    }

    private function transform(Collection $diskUsages): Collection
    {
        $diskUsages->transform(function ($item, $key) {
            return [
                $item->created_at->getPreciseTimestamp(3),
                $item->disk_usage_percentage,
            ];
        });

        return $diskUsages;
    }
}
