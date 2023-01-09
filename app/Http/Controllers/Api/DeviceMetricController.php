<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\DeviceMetric\FilterDeviceCpuTemperaturesAction;
use App\Actions\DeviceMetric\FilterDeviceCpuUsagesAction;
use App\Actions\DeviceMetric\FilterDeviceDiskUsagesAction;
use App\Actions\DeviceMetric\FilterDeviceMemoryAvailablesAction;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class DeviceMetricController
 * @package App\Http\Controllers\Api
 */
class DeviceMetricController extends Controller
{
    /**
     * Return CPU temperature data for the specified device.
     *
     * @param Request $request
     * @param FilterDeviceCpuTemperaturesAction $filterDeviceCpuTemperaturesAction
     * @param Device $device
     * @return JsonResponse
     */
    public function cpuTemperatures(
        Request $request,
        FilterDeviceCpuTemperaturesAction $filterDeviceCpuTemperaturesAction,
        Device $device
    ): JsonResponse
    {
        $data = $request->only('timeRange');
        $data['deviceId'] = $device->id;

        $cpuTemperatures = $filterDeviceCpuTemperaturesAction->execute($data);

        return $this->apiOk(['cpuTemperatures' => $cpuTemperatures->toArray()]);
    }

    /**
     * Return CPU usage data for the specified device.
     *
     * @param Request $request
     * @param FilterDeviceCpuUsagesAction $filterDeviceCpuUsagesAction
     * @param Device $device
     * @return JsonResponse
     */
    public function cpuUsages(
        Request $request,
        FilterDeviceCpuUsagesAction $filterDeviceCpuUsagesAction,
        Device $device
    ): JsonResponse
    {
        $data = $request->only('timeRange');
        $data['deviceId'] = $device->id;

        $cpuUsages = $filterDeviceCpuUsagesAction->execute($data);

        return $this->apiOk(['cpuUsages' => $cpuUsages->toArray()]);
    }

    /**
     * Return disk usage data for the specified device.
     *
     * @param Request $request
     * @param FilterDeviceDiskUsagesAction $filterDeviceDiskUsagesAction
     * @param Device $device
     * @return JsonResponse
     */
    public function diskUsages(
        Request $request,
        FilterDeviceDiskUsagesAction $filterDeviceDiskUsagesAction,
        Device $device
    ): JsonResponse
    {
        $data = $request->only('timeRange');
        $data['deviceId'] = $device->id;

        $diskUsages = $filterDeviceDiskUsagesAction->execute($data);

        return $this->apiOk(['diskUsages' => $diskUsages->toArray()]);
    }

    /**
     * Return memory usage data for the specified device.
     *
     * @param Request $request
     * @param FilterDeviceMemoryAvailablesAction $filterDeviceMemoryAvailablesAction
     * @param Device $device
     * @return JsonResponse
     */
    public function memoryAvailables(
        Request $request,
        FilterDeviceMemoryAvailablesAction $filterDeviceMemoryAvailablesAction,
        Device $device
    ): JsonResponse
    {
        $data = $request->only('timeRange');
        $data['deviceId'] = $device->id;

        $availableMemories = $filterDeviceMemoryAvailablesAction->execute($data);

        return $this->apiOk(['availableMemories' => $availableMemories->toArray()]);
    }
}
