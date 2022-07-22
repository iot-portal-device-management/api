<?php

namespace App\Actions\Metrics;

use App\Models\DiskStatistic;
use Illuminate\Database\Eloquent\Collection;

class FilterDeviceDiskUsagesAction
{
    public function execute(string $deviceId, array $data): Collection
    {
        $timeRangeFilter = (int)($data['timeRangeFilter'] ?? 1);

        $diskUsages = DiskStatistic::deviceId($deviceId)
            ->whereBetween('created_at', [now()->subHours($timeRangeFilter), now()])
            ->orderBy('created_at')
            ->get(['id', 'disk_percentage_used', 'created_at']);

        return $this->transform($diskUsages);
    }

    private function transform(Collection $diskUsages): Collection
    {
        $diskUsages->transform(function ($item, $key) {
            return [
                $item->created_at->getPreciseTimestamp(3),
                $item->disk_percentage_used,
            ];
        });

        return $diskUsages;
    }
}
