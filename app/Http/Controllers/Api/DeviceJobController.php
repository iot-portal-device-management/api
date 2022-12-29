<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\DeviceJob\CalculateDeviceJobProgressStatusAction;
use App\Actions\DeviceJob\CreateDeviceJobAction;
use App\Actions\DeviceJob\FilterDataTableDeviceJobDeviceCommandsAction;
use App\Actions\DeviceJob\FilterDataTableDeviceJobsAction;
use App\Actions\DeviceJob\FindDeviceJobByIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceJobRequest;
use App\Http\Requests\ValidateDeviceJobFieldsRequest;
use App\Http\Resources\DeviceCommandCollectionPagination;
use App\Http\Resources\DeviceJobCollectionPagination;
use App\Http\Resources\DeviceJobResource;
use App\Jobs\ProcessDeviceJobJob;
use App\Models\DeviceJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceJobController
 * @package App\Http\Controllers\Api
 */
class DeviceJobController extends Controller
{
    /**
     * Return a listing of the device job.
     *
     * @param Request $request
     * @param FilterDataTableDeviceJobsAction $filterDataTableDeviceJobsAction
     * @return JsonResponse
     */
    public function index(
        Request $request,
        FilterDataTableDeviceJobsAction $filterDataTableDeviceJobsAction
    ): JsonResponse
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $deviceJobs = $filterDataTableDeviceJobsAction->execute($data);

        return $this->apiOk(['deviceJobs' => new DeviceJobCollectionPagination($deviceJobs)]);
    }

    /**
     * Store a newly created device job in storage.
     *
     * @param StoreDeviceJobRequest $request
     * @param CreateDeviceJobAction $createDeviceJobAction
     * @return JsonResponse
     */
    public function store(StoreDeviceJobRequest $request, CreateDeviceJobAction $createDeviceJobAction): JsonResponse
    {
        $data = $request->validated();
        $data['userId'] = Auth::id();

        $deviceJob = $createDeviceJobAction->execute($data);

        if ($deviceJob->exists) {
            ProcessDeviceJobJob::dispatch($deviceJob);
            return $this->apiOk(['deviceJob' => new DeviceJobResource($deviceJob)]);
        }

        return $this->apiInternalServerError('Failed to create device job.');
    }

    /**
     * Return the specified device job.
     *
     * @param FindDeviceJobByIdAction $findDeviceJobByIdAction
     * @param DeviceJob $deviceJob
     * @return JsonResponse
     */
    public function show(FindDeviceJobByIdAction $findDeviceJobByIdAction, DeviceJob $deviceJob): JsonResponse
    {
        $deviceJob = $findDeviceJobByIdAction->execute($deviceJob->id);

        return $this->apiOk(['deviceJob' => new DeviceJobResource($deviceJob)]);
    }

    /**
     * Return the status of the device job.
     *
     * @param CalculateDeviceJobProgressStatusAction $calculateDeviceJobProgressStatusAction
     * @param DeviceJob $deviceJob
     * @return JsonResponse
     */
    public function showProgressStatus(
        CalculateDeviceJobProgressStatusAction $calculateDeviceJobProgressStatusAction,
        DeviceJob $deviceJob
    ): JsonResponse
    {
        $deviceJobProgressStatus = $calculateDeviceJobProgressStatusAction->execute($deviceJob->id);

        return $this->apiOk(['progressStatus' => $deviceJobProgressStatus]);
    }

    /**
     * Return a listing of the device job's device commands.
     *
     * @param Request $request
     * @param FilterDataTableDeviceJobDeviceCommandsAction $filterDataTableDeviceJobDeviceCommandsAction
     * @param DeviceJob $deviceJob
     * @return JsonResponse
     */
    public function deviceJobDeviceCommandsIndex(
        Request $request,
        FilterDataTableDeviceJobDeviceCommandsAction $filterDataTableDeviceJobDeviceCommandsAction,
        DeviceJob $deviceJob
    ): JsonResponse
    {
        $data = $request->all();
        $data['deviceJobId'] = $deviceJob->id;

        $deviceCommands = $filterDataTableDeviceJobDeviceCommandsAction->execute($data);

        return $this->apiOk(['deviceCommands' => new DeviceCommandCollectionPagination($deviceCommands)]);
    }
}
