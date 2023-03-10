<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceMetric;

use App\Models\DeviceMemoryStatistic;
use Illuminate\Database\Eloquent\Collection;

class FilterDeviceMemoryAvailablesAction
{
    public function execute(array $data): Collection
    {
        $timeRangeFilter = (int)($data['timeRangeFilter'] ?? 1);

        $availableMemories = DeviceMemoryStatistic::deviceId($data['deviceId'])
            ->whereBetween('created_at', [now()->subHours($timeRangeFilter), now()])
            ->orderBy('created_at')
            ->get(['id', 'available_memory_in_bytes', 'created_at']);

        return $this->transform($availableMemories);
    }

    private function transform(Collection $availableMemories): Collection
    {
        $availableMemories->transform(function ($item, $key) {
            return [
                $item->created_at->getPreciseTimestamp(3),
                $item->available_memory_in_bytes,
            ];
        });

        return $availableMemories;
    }
}
