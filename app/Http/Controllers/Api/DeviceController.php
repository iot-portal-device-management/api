<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\Device\CreateDeviceAction;
use App\Actions\Device\DeleteDevicesByIdsAction;
use App\Actions\Device\FilterDataTableDevicesAction;
use App\Actions\Device\FindDeviceByIdAction;
use App\Actions\Device\RegisterDeviceAction;
use App\Actions\Device\UpdateDeviceByIdAction;
use App\Exceptions\InvalidDeviceConnectionKeyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedDevicesRequest;
use App\Http\Requests\RegisterDeviceRequest;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Http\Resources\DeviceCollectionPagination;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceController
 * @package App\Http\Controllers\Api
 */
class DeviceController extends Controller
{
    /**
     * Return a listing of the devices.
     *
     * @param Request $request
     * @param FilterDataTableDevicesAction $filterDataTableDevicesAction
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableDevicesAction $filterDataTableDevicesAction): JsonResponse
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $devices = $filterDataTableDevicesAction->execute($data);

        return $this->apiOk(['devices' => new DeviceCollectionPagination($devices)]);
    }

    /**
     * Store a newly created device in storage.
     *
     * @param StoreDeviceRequest $request
     * @param CreateDeviceAction $createDeviceAction
     * @return JsonResponse
     */
    public function store(StoreDeviceRequest $request, CreateDeviceAction $createDeviceAction): JsonResponse
    {
        $data = $request->validated();
        $data['userId'] = Auth::id();

        $device = $createDeviceAction->execute($data);

        return $this->apiOk(['device' => new DeviceResource($device)]);
    }

    /**
     * Return the specified device.
     *
     * @param FindDeviceByIdAction $findDeviceByIdAction
     * @param Device $device
     * @return JsonResponse
     */
    public function show(FindDeviceByIdAction $findDeviceByIdAction, Device $device): JsonResponse
    {
        $device = $findDeviceByIdAction->execute($device->id);

        return $this->apiOk(['device' => new DeviceResource($device)]);
    }

    /**
     * Update the specified device in storage.
     *
     * @param UpdateDeviceRequest $request
     * @param UpdateDeviceByIdAction $updateDeviceByIdAction
     * @param Device $device
     * @return JsonResponse
     */
    public function update(
        UpdateDeviceRequest $request,
        UpdateDeviceByIdAction $updateDeviceByIdAction,
        Device $device
    ): JsonResponse
    {
        $data = $request->validated();
        $data['deviceId'] = $device->id;

        $success = $updateDeviceByIdAction->execute($data);

        return $success
            ? $this->apiOk(['device' => new DeviceResource(
                Device::id($device->id)
                    ->with(
                        'deviceCategory:id,name',
                        'deviceStatus:id,name',
                    )
                    ->firstOrFail()
            )])
            : $this->apiInternalServerError('Failed to update device.');
    }

    /**
     * Remove the specified devices from storage.
     *
     * @param DestroySelectedDevicesRequest $request
     * @param DeleteDevicesByIdsAction $deleteDevicesByIdsAction
     * @return JsonResponse
     */
    public function destroySelected(
        DestroySelectedDevicesRequest $request,
        DeleteDevicesByIdsAction $deleteDevicesByIdsAction
    ): JsonResponse
    {
        $success = $deleteDevicesByIdsAction->execute($request->ids);

        return $success
            ? $this->apiOk()
            : $this->apiInternalServerError('Failed to delete devices');
    }

    /**
     * Handle device registration request from client devices.
     *
     * @param RegisterDeviceRequest $request
     * @param RegisterDeviceAction $registerDeviceAction
     * @return JsonResponse
     * @throws InvalidDeviceConnectionKeyException
     */
    public function register(RegisterDeviceRequest $request, RegisterDeviceAction $registerDeviceAction): JsonResponse
    {
        $data = $request->validated();
        $data['deviceConnectionKey'] = $request->bearerToken();

        $device = $registerDeviceAction->execute($data);

        return $this->apiOk([
            'mqttEndpoint' => config(
                'mqtt_client.connections.'
                . config('mqtt_client.default_connection')
                . '.external_endpoint'
            ),
            'device' => new DeviceResource($device),
        ]);
    }
}
