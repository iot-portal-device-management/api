<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\DeviceStatistic\FilterOnlineDevicesAvailableMemoriesChartAction;
use App\Actions\DeviceStatistic\FilterOnlineDevicesCpuTemperaturesChartAction;
use App\Actions\DeviceStatistic\FilterOnlineDevicesCpuUsagesChartAction;
use App\Actions\DeviceStatistic\FilterOnlineDevicesDiskUsagesChartAction;
use App\Actions\DeviceStatistic\GetLastSevenDayNewDeviceCategoryCountAction;
use App\Actions\DeviceStatistic\GetLastSevenDayNewDeviceCountAction;
use App\Actions\DeviceStatistic\GetLastSevenDayNewDeviceGroupCountAction;
use App\Actions\DeviceStatistic\GetLastSevenDayNewDeviceJobCountAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceAvailableMemoryStatisticResource;
use App\Http\Resources\DeviceCpuTemperatureStatisticResource;
use App\Http\Resources\DeviceCpuUsageStatisticResource;
use App\Http\Resources\DeviceDiskUsageStatisticResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class StatisticController
 * @package App\Http\Controllers\Api
 */
class StatisticController extends Controller
{
    /**
     * Return the overall statistics for the logged in user.
     *
     * @param GetLastSevenDayNewDeviceCountAction $getLastSevenDayNewDeviceCountAction
     * @param GetLastSevenDayNewDeviceGroupCountAction $getLastSevenDayNewDeviceGroupCountAction
     * @param GetLastSevenDayNewDeviceCategoryCountAction $getLastSevenDayNewDeviceCategoryCountAction
     * @param GetLastSevenDayNewDeviceJobCountAction $getLastSevenDayNewDeviceJobCountAction
     * @return JsonResponse
     */
    public function showStatistics(
        GetLastSevenDayNewDeviceCountAction $getLastSevenDayNewDeviceCountAction,
        GetLastSevenDayNewDeviceGroupCountAction $getLastSevenDayNewDeviceGroupCountAction,
        GetLastSevenDayNewDeviceCategoryCountAction $getLastSevenDayNewDeviceCategoryCountAction,
        GetLastSevenDayNewDeviceJobCountAction $getLastSevenDayNewDeviceJobCountAction
    ): JsonResponse
    {
        // Device
        $deviceTotal = Auth::user()->devices()->count();
        $lastSevenDayNewDeviceCount = $getLastSevenDayNewDeviceCountAction->execute();

        // Device groups
        $deviceGroupTotal = Auth::user()->deviceGroups()->count();
        $lastSevenDayNewDeviceGroupCount = $getLastSevenDayNewDeviceGroupCountAction->execute();

        // Device categories
        $deviceCategoryTotal = Auth::user()->deviceCategories()->count();
        $lastSevenDayNewDeviceCategoryCount = $getLastSevenDayNewDeviceCategoryCountAction->execute();

        // Device jobs
        $deviceJobTotal = Auth::user()->deviceJobs()->count();
        $lastSevenDayNewDeviceJobCount = $getLastSevenDayNewDeviceJobCountAction->execute();

        return $this->apiOk([
            'statistics' => [
                'deviceTotal' => $deviceTotal,
                'lastSevenDayNewDeviceCount' => $lastSevenDayNewDeviceCount,
                'deviceGroupTotal' => $deviceGroupTotal,
                'lastSevenDayNewDeviceGroupCount' => $lastSevenDayNewDeviceGroupCount,
                'deviceCategoryTotal' => $deviceCategoryTotal,
                'lastSevenDayNewDeviceCategoryCount' => $lastSevenDayNewDeviceCategoryCount,
                'deviceJobTotal' => $deviceJobTotal,
                'lastSevenDayNewDeviceJobCount' => $lastSevenDayNewDeviceJobCount,
            ]
        ]);
    }

    /**
     * Return device CPU temperatures chart data.
     *
     * @param Request $request
     * @param FilterOnlineDevicesCpuTemperaturesChartAction $filterOnlineDevicesCpuTemperaturesGraphAction
     * @return JsonResponse
     */
    public function onlineDevicesCpuTemperatures(
        Request $request,
        FilterOnlineDevicesCpuTemperaturesChartAction $filterOnlineDevicesCpuTemperaturesGraphAction
    ): JsonResponse
    {
        $data = $request->only('timeRangeFilter');
        $data['userId'] = Auth::id();

        $cpuTemperatures = $filterOnlineDevicesCpuTemperaturesGraphAction->execute($data);

        return $this->apiOk(['cpuTemperatures' => new DeviceCpuTemperatureStatisticResource($cpuTemperatures)]);
    }

    /**
     * Return device CPU usages chart data.
     *
     * @param Request $request
     * @param FilterOnlineDevicesCpuUsagesChartAction $filterOnlineDevicesCpuUsagesChartAction
     * @return JsonResponse
     */
    public function onlineDevicesCpuUsages(
        Request $request,
        FilterOnlineDevicesCpuUsagesChartAction $filterOnlineDevicesCpuUsagesChartAction
    ): JsonResponse
    {
        $data = $request->only('timeRangeFilter');
        $data['userId'] = Auth::id();

        $cpuUsages = $filterOnlineDevicesCpuUsagesChartAction->execute($data);

        return $this->apiOk(['cpuUsages' => new DeviceCpuUsageStatisticResource($cpuUsages)]);
    }

    /**
     * Return device disk usages chart data.
     *
     * @param Request $request
     * @param FilterOnlineDevicesDiskUsagesChartAction $filterOnlineDevicesDiskUsagesChartAction
     * @return JsonResponse
     */
    public function onlineDevicesDiskUsages(
        Request $request,
        FilterOnlineDevicesDiskUsagesChartAction $filterOnlineDevicesDiskUsagesChartAction
    ): JsonResponse
    {
        $data = $request->only('timeRangeFilter');
        $data['userId'] = Auth::id();

        $diskUsages = $filterOnlineDevicesDiskUsagesChartAction->execute($data);

        return $this->apiOk(['diskUsages' => new DeviceDiskUsageStatisticResource($diskUsages)]);
    }

    /**
     * Return device available memory chart data.
     *
     * @param Request $request
     * @param FilterOnlineDevicesAvailableMemoriesChartAction $filterOnlineDevicesAvailableMemoriesChartAction
     * @return JsonResponse
     */
    public function onlineDevicesMemoryAvailables(
        Request $request,
        FilterOnlineDevicesAvailableMemoriesChartAction $filterOnlineDevicesAvailableMemoriesChartAction
    ): JsonResponse
    {
        $data = $request->only('timeRangeFilter');
        $data['userId'] = Auth::id();

        $availableMemories = $filterOnlineDevicesAvailableMemoriesChartAction->execute($data);

        return $this->apiOk(['availableMemories' => new DeviceAvailableMemoryStatisticResource($availableMemories)]);
    }
}
