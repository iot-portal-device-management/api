<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\DeviceEvent\FilterDataTableDeviceEventsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceEventCollectionPagination;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class DeviceEventController
 * @package App\Http\Controllers\Api
 */
class DeviceEventController extends Controller
{
    /**
     * Return a listing of the device events.
     *
     * @param Request $request
     * @param FilterDataTableDeviceEventsAction $filterDataTableDeviceEventsAction
     * @param Device $device
     * @return JsonResponse
     */
    public function index(
        Request $request,
        FilterDataTableDeviceEventsAction $filterDataTableDeviceEventsAction,
        Device $device
    ): JsonResponse
    {
        $data = $request->all();
        $data['deviceId'] = $device->id;

        $deviceEvents = $filterDataTableDeviceEventsAction->execute($data);

        return $this->apiOk(['deviceEvents' => new DeviceEventCollectionPagination($deviceEvents)]);
    }
}
