<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceMetric\FilterDeviceCpuTemperaturesAction;
use App\Actions\DeviceMetric\FilterDeviceCpuUsagesAction;
use App\Actions\DeviceMetric\FilterDeviceDiskUsagesAction;
use App\Actions\DeviceMetric\FilterDeviceMemoryAvailablesAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class DeviceMetricController
 * @package App\Http\Controllers\Api
 */
class DeviceMetricController extends Controller
{
//    /**
//     * DeviceMetricController constructor.
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
    public function cpuTemperatures(
        Request $request,
        FilterDeviceCpuTemperaturesAction $filterDeviceCpuTemperaturesAction,
        string $deviceId
    ): JsonResponse
    {
        $data = $request->only('timeRange');
        $data['deviceId'] = $deviceId;

        $cpuTemperatures = $filterDeviceCpuTemperaturesAction->execute($data);

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
    public function cpuUsages(
        Request $request,
        FilterDeviceCpuUsagesAction $filterDeviceCpuUsagesAction,
        string $deviceId
    ): JsonResponse
    {
        $data = $request->only('timeRange');
        $data['deviceId'] = $deviceId;

        $cpuUsages = $filterDeviceCpuUsagesAction->execute($data);

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
    public function diskUsages(
        Request $request,
        FilterDeviceDiskUsagesAction $filterDeviceDiskUsagesAction,
        string $deviceId
    ): JsonResponse
    {
        $data = $request->only('timeRange');
        $data['deviceId'] = $deviceId;

        $diskUsages = $filterDeviceDiskUsagesAction->execute($data);

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
    public function memoryAvailables(
        Request $request,
        FilterDeviceMemoryAvailablesAction $filterDeviceMemoryAvailablesAction,
        string $deviceId
    ): JsonResponse
    {
        $data = $request->only('timeRange');
        $data['deviceId'] = $deviceId;

        $availableMemories = $filterDeviceMemoryAvailablesAction->execute($data);

        return $this->apiOk(['availableMemories' => $availableMemories->toArray()]);
    }
}
