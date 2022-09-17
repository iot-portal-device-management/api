<?php

namespace App\Actions\DeviceMetric;

use App\Models\DeviceCpuStatistic;
use Illuminate\Database\Eloquent\Collection;

class FilterDeviceCpuUsagesAction
{
    public function execute(string $deviceId, array $data): Collection
    {
        $timeRangeFilter = (int)($data['timeRangeFilter'] ?? 1);

        $cpuUsages = DeviceCpuStatistic::deviceId($deviceId)
            ->whereBetween('created_at', [now()->subHours($timeRangeFilter), now()])
            ->orderBy('created_at')
            ->get(['id', 'cpu_usage_percentage', 'created_at']);

        return $this->transform($cpuUsages);
    }

    private function transform(Collection $cpuUsages): Collection
    {
        $cpuUsages->transform(function ($item, $key) {
            return [
                $item->created_at->getPreciseTimestamp(3),
                $item->cpu_usage_percentage,
            ];
        });

        return $cpuUsages;
    }
}
