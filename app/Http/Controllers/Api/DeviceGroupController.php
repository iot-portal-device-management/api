<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\DeviceGroup\CreateDeviceGroupAction;
use App\Actions\DeviceGroup\DeleteDeviceGroupsByIdsAction;
use App\Actions\DeviceGroup\FilterDataTableDeviceGroupDevicesAction;
use App\Actions\DeviceGroup\FilterDataTableDeviceGroupsAction;
use App\Actions\DeviceGroup\FindDeviceGroupByIdAction;
use App\Actions\DeviceGroup\UpdateDeviceGroupByIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedDeviceGroupsRequest;
use App\Http\Requests\StoreDeviceGroupRequest;
use App\Http\Requests\UpdateDeviceGroupRequest;
use App\Http\Resources\DeviceCollectionPagination;
use App\Http\Resources\DeviceGroupCollectionPagination;
use App\Http\Resources\DeviceGroupResource;
use App\Models\DeviceGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceGroupController
 * @package App\Http\Controllers\Api
 */
class DeviceGroupController extends Controller
{
    /**
     * Return a listing of the device group.
     *
     * @param Request $request
     * @param FilterDataTableDeviceGroupsAction $filterDataTableDeviceGroupAction
     * @return JsonResponse
     */
    public function index(
        Request $request,
        FilterDataTableDeviceGroupsAction $filterDataTableDeviceGroupAction
    ): JsonResponse
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $deviceGroups = $filterDataTableDeviceGroupAction->execute($data);

        return $this->apiOk(['deviceGroups' => new DeviceGroupCollectionPagination($deviceGroups)]);
    }

    /**
     * Store a newly created device group in storage.
     *
     * @param StoreDeviceGroupRequest $request
     * @param CreateDeviceGroupAction $createDeviceGroupAction
     * @return JsonResponse
     */
    public function store(
        StoreDeviceGroupRequest $request,
        CreateDeviceGroupAction $createDeviceGroupAction
    ): JsonResponse
    {
        $data = $request->validated();
        $data['userId'] = Auth::id();

        $deviceGroup = $createDeviceGroupAction->execute($data);

        return $this->apiOk(['deviceGroup' => new DeviceGroupResource($deviceGroup)]);
    }

    /**
     * Return the specified device group.
     *
     * @param DeviceGroup $deviceGroup
     * @return JsonResponse
     */
    public function show(DeviceGroup $deviceGroup): JsonResponse
    {
        return $this->apiOk(['deviceGroup' => new DeviceGroupResource($deviceGroup)]);
    }

    /**
     * Update the specified device group in storage.
     *
     * @param UpdateDeviceGroupRequest $request
     * @param UpdateDeviceGroupByIdAction $updateDeviceGroupByIdAction
     * @param FindDeviceGroupByIdAction $findDeviceGroupByIdAction
     * @param DeviceGroup $deviceGroup
     * @return JsonResponse
     */
    public function update(
        UpdateDeviceGroupRequest $request,
        UpdateDeviceGroupByIdAction $updateDeviceGroupByIdAction,
        FindDeviceGroupByIdAction $findDeviceGroupByIdAction,
        DeviceGroup $deviceGroup
    ): JsonResponse
    {
        $data = $request->validated();
        $data['deviceGroupId'] = $deviceGroup->id;

        $success = $updateDeviceGroupByIdAction->execute($data);

        return $success
            ? $this->apiOk(['deviceGroup' => new DeviceGroupResource($findDeviceGroupByIdAction->execute($deviceGroup->id))])
            : $this->apiInternalServerError('Failed to update device group');
    }

    /**
     * Remove the specified device groups from storage.
     *
     * @param DestroySelectedDeviceGroupsRequest $request
     * @param DeleteDeviceGroupsByIdsAction $deleteDeviceGroupsByIdsAction
     * @return JsonResponse
     */
    public function destroySelected(
        DestroySelectedDeviceGroupsRequest $request,
        DeleteDeviceGroupsByIdsAction $deleteDeviceGroupsByIdsAction
    ): JsonResponse
    {
        $success = $deleteDeviceGroupsByIdsAction->execute($request->ids);

        return $success
            ? $this->apiOk()
            : $this->apiInternalServerError('Failed to delete device groups');
    }

    /**
     * Return a listing of the device group devices.
     *
     * @param Request $request
     * @param FilterDataTableDeviceGroupDevicesAction $filterDataTableDeviceGroupDevicesAction
     * @param DeviceGroup $deviceGroup
     * @return JsonResponse
     */
    public function deviceGroupDevicesIndex(
        Request $request,
        FilterDataTableDeviceGroupDevicesAction $filterDataTableDeviceGroupDevicesAction,
        DeviceGroup $deviceGroup,
    ): JsonResponse
    {
        $data = $request->all();
        $data['deviceGroupId'] = $deviceGroup->id;

        $devices = $filterDataTableDeviceGroupDevicesAction->execute($data);

        return $this->apiOk(['devices' => new DeviceCollectionPagination($devices)]);
    }


    /**
     * Return device group options for user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        $query = Auth::user()->deviceGroups();

        if ($request->has('name')) {
            $query->nameILike($request->name);
        }

        return $this->apiOk(['deviceGroups' => $query->getOptions()]);
    }
}
