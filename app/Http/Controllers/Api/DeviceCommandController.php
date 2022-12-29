<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\DeviceCommand\FilterDataTableDeviceCommandsAction;
use App\Actions\DeviceCommandType\TriggerDeviceCommandAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\TriggerDeviceCommandRequest;
use App\Http\Resources\DeviceCommandCollectionPagination;
use App\Http\Resources\DeviceCommandResource;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class DeviceCommandController
 * @package App\Http\Controllers\Api
 */
class DeviceCommandController extends Controller
{
    /**
     * Return a listing of the device commands.
     *
     * @param Request $request
     * @param FilterDataTableDeviceCommandsAction $filterDataTableDeviceCommandsAction
     * @param Device $device
     * @return JsonResponse
     */
    public function index(
        Request $request,
        FilterDataTableDeviceCommandsAction $filterDataTableDeviceCommandsAction,
        Device $device
    ): JsonResponse
    {
        $data = $request->all();
        $data['deviceId'] = $device->id;

        $deviceCommands = $filterDataTableDeviceCommandsAction->execute($data);

        return $this->apiOk(['deviceCommands' => new DeviceCommandCollectionPagination($deviceCommands)]);
    }

    /**
     * Trigger device command.
     *
     * @param TriggerDeviceCommandRequest $request
     * @param TriggerDeviceCommandAction $triggerDeviceCommandAction
     * @param Device $device
     * @return JsonResponse
     */
    public function triggerDeviceCommand(
        TriggerDeviceCommandRequest $request,
        TriggerDeviceCommandAction $triggerDeviceCommandAction,
        Device $device
    ): JsonResponse
    {
        $data = $request->validated();
        $data['deviceId'] = $device->id;

        $deviceCommand = $triggerDeviceCommandAction->execute($data);

        return $this->apiOk(['deviceCommand' => new DeviceCommandResource($deviceCommand)]);
    }
}
