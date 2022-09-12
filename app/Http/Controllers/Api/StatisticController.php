<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceStatistic\FilterOnlineDeviceAvailableMemoriesChartAction;
use App\Actions\DeviceStatistic\FilterOnlineDeviceCpuTemperaturesChartAction;
use App\Actions\DeviceStatistic\FilterOnlineDeviceCpuUsagesChartAction;
use App\Actions\DeviceStatistic\FilterOnlineDeviceDiskUsagesChartAction;
use App\Actions\DeviceStatistic\GetLastSevenDayNewDeviceCategoryCountAction;
use App\Actions\DeviceStatistic\GetLastSevenDayNewDeviceCountAction;
use App\Actions\DeviceStatistic\GetLastSevenDayNewDeviceGroupCountAction;
use App\Actions\DeviceStatistic\GetLastSevenDayNewDeviceJobCountAction;
use App\Http\Controllers\Controller;
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
     * Return the overall statistics for the logged in user on dashboard.
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
     * Return CPU temperatures chart data.
     *
     * @param Request $request
     * @param FilterOnlineDeviceCpuTemperaturesChartAction $filterOnlineDeviceCpuTemperaturesGraphAction
     * @return JsonResponse
     */
    public function cpuTemperatures(
        Request $request,
        FilterOnlineDeviceCpuTemperaturesChartAction $filterOnlineDeviceCpuTemperaturesGraphAction
    ): JsonResponse
    {
        $cpuTemperatures = $filterOnlineDeviceCpuTemperaturesGraphAction->execute($request->user(), $request->only('timeRangeFilter'));

        return $this->apiOk(['cpuTemperatures' => $cpuTemperatures]);
    }

    /**
     * Return CPU usages chart data.
     *
     * @param Request $request
     * @param FilterOnlineDeviceCpuUsagesChartAction $filterOnlineDeviceCpuUsagesChartAction
     * @return JsonResponse
     */
    public function cpuUsages(
        Request $request,
        FilterOnlineDeviceCpuUsagesChartAction $filterOnlineDeviceCpuUsagesChartAction
    ): JsonResponse
    {
        $cpuUsages = $filterOnlineDeviceCpuUsagesChartAction->execute($request->user(), $request->only('timeRangeFilter'));

        return $this->apiOk(['cpuUsages' => $cpuUsages]);
    }

    /**
     * Return disk usages chart data.
     *
     * @param Request $request
     * @param FilterOnlineDeviceDiskUsagesChartAction $filterOnlineDeviceDiskUsagesChartAction
     * @return JsonResponse
     */
    public function diskUsages(
        Request $request,
        FilterOnlineDeviceDiskUsagesChartAction $filterOnlineDeviceDiskUsagesChartAction
    ): JsonResponse
    {
        $diskUsages = $filterOnlineDeviceDiskUsagesChartAction->execute($request->user(), $request->only('timeRangeFilter'));

        return $this->apiOk(['diskUsages' => $diskUsages]);
    }

    /**
     * Return memory usages chart data.
     *
     * @param Request $request
     * @param FilterOnlineDeviceAvailableMemoriesChartAction $filterOnlineDeviceAvailableMemoriesChartAction
     * @return JsonResponse
     */
    public function memoryAvailables(
        Request $request,
        FilterOnlineDeviceAvailableMemoriesChartAction $filterOnlineDeviceAvailableMemoriesChartAction
    ): JsonResponse
    {
        $availableMemories = $filterOnlineDeviceAvailableMemoriesChartAction->execute($request->user(), $request->only('timeRangeFilter'));

        return $this->apiOk(['availableMemories' => $availableMemories]);
    }
}
