<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceJob;

use App\Models\DeviceCommand;
use App\Models\DeviceCommandStatus;
use Illuminate\Database\Eloquent\Builder;

class CalculateDeviceJobProgressStatusAction
{
    public function execute(string $id): array
    {
        $totalCount = DeviceCommand::deviceJobId($id)
            ->count();

        if ($totalCount === 0) {
            $pendingCount = $processingCount = $successfulCount = $failedCount = $progress = 0;
        } else {
            $pendingCount = DeviceCommand::deviceJobId($id)
                ->whereHas('deviceCommandStatus', function (Builder $query) {
                    $query->ofStatus(DeviceCommandStatus::STATUS_PENDING);
                })
                ->count();

            $processingCount = DeviceCommand::deviceJobId($id)
                ->whereHas('deviceCommandStatus', function (Builder $query) {
                    $query->ofStatus(DeviceCommandStatus::STATUS_PROCESSING);
                })
                ->count();

            $successfulCount = DeviceCommand::deviceJobId($id)
                ->whereHas('deviceCommandStatus', function (Builder $query) {
                    $query->ofStatus(DeviceCommandStatus::STATUS_SUCCESSFUL);
                })
                ->count();

            $failedCount = DeviceCommand::deviceJobId($id)
                ->whereHas('deviceCommandStatus', function (Builder $query) {
                    $query->ofStatus(DeviceCommandStatus::STATUS_FAILED);
                })
                ->count();

            // Prevent division by zero error
            $progress = ($successfulCount + $failedCount) / ($totalCount ?? 1) * 100 ?? 0;
        }

        return [
            'total' => $totalCount,
            'pending' => $pendingCount,
            'processing' => $processingCount,
            'successful' => $successfulCount,
            'failed' => $failedCount,
            'progress' => $progress,
        ];
    }
}
