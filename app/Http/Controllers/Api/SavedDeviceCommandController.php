<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Actions\SavedDeviceCommand\CreateSavedDeviceCommandAction;
use App\Actions\SavedDeviceCommand\DeleteSavedDeviceCommandsByIdsAction;
use App\Actions\SavedDeviceCommand\FilterDataTableSavedDeviceCommandsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedSavedDeviceCommandsRequest;
use App\Http\Requests\StoreSavedDeviceCommandRequest;
use App\Http\Resources\SavedDeviceCommandCollectionPagination;
use App\Http\Resources\SavedDeviceCommandResource;
use App\Models\SavedDeviceCommand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class SavedDeviceCommandController
 * @package App\Http\Controllers\Api
 */
class SavedDeviceCommandController extends Controller
{
    /**
     * Return a listing of the saved device commands.
     *
     * @param Request $request
     * @param FilterDataTableSavedDeviceCommandsAction $filterDataTableSavedCommandsAction
     * @return JsonResponse
     */
    public function index(
        Request $request,
        FilterDataTableSavedDeviceCommandsAction $filterDataTableSavedCommandsAction
    ): JsonResponse
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $savedDeviceCommands = $filterDataTableSavedCommandsAction->execute($data);

        return $this->apiOk(['savedDeviceCommands' => new SavedDeviceCommandCollectionPagination($savedDeviceCommands)]);
    }

    /**
     * Store a newly created saved device command in storage.
     *
     * @param StoreSavedDeviceCommandRequest $request
     * @param CreateSavedDeviceCommandAction $createSavedDeviceCommandAction
     * @return JsonResponse
     */
    public function store(
        StoreSavedDeviceCommandRequest $request,
        CreateSavedDeviceCommandAction $createSavedDeviceCommandAction
    ): JsonResponse
    {
        $data = $request->validated();
        $data['userId'] = Auth::id();

        $savedDeviceCommand = $createSavedDeviceCommandAction->execute($data);

        return $this->apiOk(['savedDeviceCommand' => new SavedDeviceCommandResource($savedDeviceCommand)]);
    }

    /**
     * Return the specified saved device command.
     *
     * @param SavedDeviceCommand $savedDeviceCommand
     * @return JsonResponse
     */
    public function show(SavedDeviceCommand $savedDeviceCommand): JsonResponse
    {
        return $this->apiOk(['savedDeviceCommand' => new SavedDeviceCommandResource($savedDeviceCommand)]);
    }

    /**
     * Remove the specified saved device commands from storage.
     *
     * @param DestroySelectedSavedDeviceCommandsRequest $request
     * @param DeleteSavedDeviceCommandsByIdsAction $deleteSavedDeviceCommandsAction
     * @return JsonResponse
     */
    public function destroySelected(
        DestroySelectedSavedDeviceCommandsRequest $request,
        DeleteSavedDeviceCommandsByIdsAction $deleteSavedDeviceCommandsAction
    ): JsonResponse
    {
        $success = $deleteSavedDeviceCommandsAction->execute($request->ids);

        return $success
            ? $this->apiOk()
            : $this->apiInternalServerError('Failed to delete saved device commands');
    }

    /**
     * Return the saved device command options available for user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        $query = Auth::user()->savedDeviceCommands();

        if ($request->has('name')) {
            $query->nameILike($request->name);
        }

        return $this->apiOk(['savedDeviceCommands' => $query->getOptions()]);
    }
}
