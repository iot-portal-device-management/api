<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceStatistic;

use App\Models\Device;
use App\Models\DeviceStatus;
use Illuminate\Support\Collection;

class FilterOnlineDevicesDiskUsagesChartAction
{
    public function execute(array $data): Collection
    {
        $timeRangeFilter = (int)($data['timeRangeFilter'] ?? 1);

        $devices = Device::userId($data['userId'])
            ->with(['deviceDiskStatistics' => function ($query) use ($timeRangeFilter) {
                $query->whereBetween('created_at', [now()->subHours($timeRangeFilter), now()])
                    ->select('disk_usage_percentage', 'device_id', 'created_at');
            }])
            ->whereHas('deviceStatus', function ($query) {
                $query->ofStatus(DeviceStatus::STATUS_ONLINE);
            })
            ->whereHas('deviceDiskStatistics', function ($query) use ($timeRangeFilter) {
                $query->whereBetween('created_at', [now()->subHours($timeRangeFilter), now()]);
            })
            ->limit(10)
            ->get([Device::getTableName() . '.id', Device::getTableName() . '.name']);

        $devices->transform(function ($item, $key) {
            return [
                'name' => $item->name,
                'data' => $item->deviceDiskStatistics->transform(function ($item, $key) {
                    return [
                        'timestamp' => $item->created_at->getPreciseTimestamp(3),
                        'disk_usage_percentage' => $item->disk_usage_percentage,
                    ];
                }),
            ];
        });

        return $devices;
    }
}
