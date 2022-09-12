<?php

namespace App\Actions\DeviceMetric;

use App\Models\DeviceTemperatureStatistic;
use Illuminate\Database\Eloquent\Collection;

class FilterDeviceCpuTemperaturesAction
{
    public function execute(string $deviceId, array $data): Collection
    {
        //TODO: implement max time range protection
//        $timeRange = (int)($data['timeRange'] ?? 1);
        $timeRange = (int)(10000);

        $cpuTemperatures = DeviceTemperatureStatistic::deviceId($deviceId)
            ->whereBetween('created_at', [now()->subHours($timeRange), now()])
            ->orderBy('created_at')
            ->get(['id', 'temperature', 'created_at']);

        return $this->transform($cpuTemperatures);
    }

    private function transform(Collection $cpuTemperatures): Collection
    {
        $cpuTemperatures->transform(function ($item, $key) {
            return [
                $item->created_at->getPreciseTimestamp(3),
                $item->temperature,
            ];
        });

        return $cpuTemperatures;
    }
}
