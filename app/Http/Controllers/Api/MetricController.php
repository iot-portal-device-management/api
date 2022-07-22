<?php

namespace App\Http\Controllers\Api;

use App\Actions\Metrics\FilterDeviceCpuTemperaturesAction;
use App\Actions\Metrics\FilterDeviceCpuUsagesAction;
use App\Actions\Metrics\FilterDeviceDiskUsagesAction;
use App\Actions\Metrics\FilterDeviceMemoryAvailablesAction;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class MetricController
 * @package App\Http\Controllers\Api
 */
class MetricController extends Controller
{
//    /**
//     * MetricController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:view,device')->only(['cpuTemperatures', 'cpuUsages', 'diskUsages', 'memoryAvailables']);
//    }

    /**
     * Return CPU temperature data for the specified device.
     *
     * @param Request $request
     * @param FilterDeviceCpuTemperaturesAction $filterDeviceCpuTemperaturesAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function cpuTemperatures(Request $request, FilterDeviceCpuTemperaturesAction $filterDeviceCpuTemperaturesAction, string $deviceId): JsonResponse
    {
        $cpuTemperatures = $filterDeviceCpuTemperaturesAction->execute($deviceId, $request->only('timeRange'));

        return $this->apiOk(['cpuTemperatures' => $cpuTemperatures->toArray()]);
    }

    /**
     * Return CPU usage data for the specified device.
     *
     * @param Request $request
     * @param FilterDeviceCpuUsagesAction $filterDeviceCpuUsagesAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function cpuUsages(Request $request, FilterDeviceCpuUsagesAction $filterDeviceCpuUsagesAction, string $deviceId): JsonResponse
    {
        $cpuUsages = $filterDeviceCpuUsagesAction->execute($deviceId, $request->only('timeRange'));

        return $this->apiOk(['cpuUsages' => $cpuUsages->toArray()]);
    }

    /**
     * Return disk usage data for the specified device.
     *
     * @param Request $request
     * @param FilterDeviceDiskUsagesAction $filterDeviceDiskUsagesAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function diskUsages(Request $request, FilterDeviceDiskUsagesAction $filterDeviceDiskUsagesAction, string $deviceId): JsonResponse
    {
        $diskUsages = $filterDeviceDiskUsagesAction->execute($deviceId, $request->only('timeRange'));

        return $this->apiOk(['diskUsages' => $diskUsages->toArray()]);
    }

    /**
     * Return memory usage data for the specified device.
     *
     * @param Request $request
     * @param FilterDeviceMemoryAvailablesAction $filterDeviceMemoryAvailablesAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function memoryAvailables(Request $request, FilterDeviceMemoryAvailablesAction $filterDeviceMemoryAvailablesAction, string $deviceId): JsonResponse
    {
        $availableMemories = $filterDeviceMemoryAvailablesAction->execute($deviceId, $request->only('timeRange'));

        return $this->apiOk(['availableMemories' => $availableMemories->toArray()]);
    }
}
