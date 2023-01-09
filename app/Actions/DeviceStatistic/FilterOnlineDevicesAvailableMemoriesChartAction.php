<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceStatistic;

use App\Models\Device;
use App\Models\DeviceStatus;
use Illuminate\Support\Collection;

class FilterOnlineDevicesAvailableMemoriesChartAction
{
    public function execute(array $data): Collection
    {
        //TODO: implement max time range protection
        $timeRangeFilter = (int)($data['timeRangeFilter'] ?? 1);

        $devices = Device::userId($data['userId'])
            ->with(['deviceMemoryStatistics' => function ($query) use ($timeRangeFilter) {
                $query->whereBetween('created_at', [now()->subHours($timeRangeFilter), now()])
                    ->select('available_memory_in_bytes', 'device_id', 'created_at');
            }])
            ->whereHas('deviceStatus', function ($query) {
                $query->ofStatus(DeviceStatus::STATUS_ONLINE);
            })
            ->whereHas('deviceMemoryStatistics', function ($query) use ($timeRangeFilter) {
                $query->whereBetween('created_at', [now()->subHours($timeRangeFilter), now()]);
            })
            ->limit(10)
            ->get([Device::getTableName() . '.id', Device::getTableName() . '.name']);

        $devices->transform(function ($item, $key) {
            return [
                'name' => $item->name,
                'data' => $item->deviceMemoryStatistics->transform(function ($item, $key) {
                    return [
                        'timestamp' => $item->created_at->getPreciseTimestamp(3),
                        'available_memory_in_bytes' => $item->available_memory_in_bytes,
                    ];
                }),
            ];
        });

        return $devices;
    }
}
