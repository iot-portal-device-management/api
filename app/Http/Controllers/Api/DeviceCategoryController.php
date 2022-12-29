<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\DeviceCategory\CreateDeviceCategoryAction;
use App\Actions\DeviceCategory\DeleteDeviceCategoriesByIdsAction;
use App\Actions\DeviceCategory\FilterDataTableDeviceCategoriesAction;
use App\Actions\DeviceCategory\FilterDataTableDeviceCategoryDevicesAction;
use App\Actions\DeviceCategory\FindDeviceCategoryByIdAction;
use App\Actions\DeviceCategory\UpdateDeviceCategoryByIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedDeviceCategoriesRequest;
use App\Http\Requests\StoreDeviceCategoryRequest;
use App\Http\Requests\UpdateDeviceCategoryRequest;
use App\Http\Resources\DeviceCategoryCollectionPagination;
use App\Http\Resources\DeviceCategoryResource;
use App\Http\Resources\DeviceCollectionPagination;
use App\Models\DeviceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceCategoryController
 * @package App\Http\Controllers\Api
 */
class DeviceCategoryController extends Controller
{
    /**
     * Return a listing of the device categories.
     *
     * @param Request $request
     * @param FilterDataTableDeviceCategoriesAction $filterDataTableDeviceCategoriesAction
     * @return JsonResponse
     */
    public function index(
        Request $request,
        FilterDataTableDeviceCategoriesAction $filterDataTableDeviceCategoriesAction
    ): JsonResponse
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $deviceCategories = $filterDataTableDeviceCategoriesAction->execute($data);

        return $this->apiOk(['deviceCategories' => new DeviceCategoryCollectionPagination($deviceCategories)]);
    }

    /**
     * Store a newly created device category in storage.
     *
     * @param StoreDeviceCategoryRequest $request
     * @param CreateDeviceCategoryAction $createDeviceCategoryAction
     * @return JsonResponse
     */
    public function store(
        StoreDeviceCategoryRequest $request,
        CreateDeviceCategoryAction $createDeviceCategoryAction
    ): JsonResponse
    {
        $data = $request->validated();
        $data['userId'] = Auth::id();

        $deviceCategory = $createDeviceCategoryAction->execute($data);

        return $this->apiOk(['deviceCategory' => new DeviceCategoryResource($deviceCategory)]);
    }

    /**
     * Display the specified device category.
     *
     * @param DeviceCategory $deviceCategory
     * @return JsonResponse
     */
    public function show(DeviceCategory $deviceCategory): JsonResponse
    {
        return $this->apiOk(['deviceCategory' => new DeviceCategoryResource($deviceCategory)]);
    }

    /**
     * Update the specified device category in storage.
     *
     * @param UpdateDeviceCategoryRequest $request
     * @param UpdateDeviceCategoryByIdAction $updateDeviceCategoryByIdAction
     * @param FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction
     * @param DeviceCategory $deviceCategory
     * @return JsonResponse
     */
    public function update(
        UpdateDeviceCategoryRequest $request,
        UpdateDeviceCategoryByIdAction $updateDeviceCategoryByIdAction,
        FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction,
        DeviceCategory $deviceCategory
    ): JsonResponse
    {
        $data = $request->validated();
        $data['deviceCategoryId'] = $deviceCategory->id;

        $success = $updateDeviceCategoryByIdAction->execute($data);

        return $success
            ? $this->apiOk(['deviceCategory' => new DeviceCategoryResource($findDeviceCategoryByIdAction->execute($deviceCategory->id))])
            : $this->apiInternalServerError('Failed to update device category.');
    }

    /**
     * Remove the specified device categories from storage.
     *
     * @param DestroySelectedDeviceCategoriesRequest $request
     * @param DeleteDeviceCategoriesByIdsAction $deleteDeviceCategoriesByIdsAction
     * @return JsonResponse
     */
    public function destroySelected(
        DestroySelectedDeviceCategoriesRequest $request,
        DeleteDeviceCategoriesByIdsAction $deleteDeviceCategoriesByIdsAction
    ): JsonResponse
    {
        $success = $deleteDeviceCategoriesByIdsAction->execute($request->ids);

        return $success
            ? $this->apiOk()
            : $this->apiInternalServerError('Failed to delete device categories');
    }

    /**
     * Return a listing of the device group devices.
     *
     * @param Request $request
     * @param FilterDataTableDeviceCategoryDevicesAction $filterDataTableDeviceCategoryDevicesAction
     * @param DeviceCategory $deviceCategory
     * @return JsonResponse
     */
    public function deviceCategoryDevicesIndex(
        Request $request,
        FilterDataTableDeviceCategoryDevicesAction $filterDataTableDeviceCategoryDevicesAction,
        DeviceCategory $deviceCategory,
    ): JsonResponse
    {
        $data = $request->all();
        $data['deviceCategoryId'] = $deviceCategory->id;

        $devices = $filterDataTableDeviceCategoryDevicesAction->execute($data);

        return $this->apiOk(['devices' => new DeviceCollectionPagination($devices)]);
    }

    /**
     * Return device category options for user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        $query = Auth::user()->deviceCategories();

        if ($request->has('name')) {
            $query->nameILike($request->name);
        }

        return $this->apiOk(['deviceCategories' => $query->getOptions()]);
    }
}
