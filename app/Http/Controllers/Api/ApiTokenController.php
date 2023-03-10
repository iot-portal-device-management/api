<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ApiTokenController
 * @package App\Http\Controllers\Api
 */
class ApiTokenController extends Controller
{
    /**
     * Return user's unique id and device connection key.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->apiOk([
            'uniqueId' => $user->unique_id,
            'deviceConnectionKey' => $user->device_connection_key,
        ]);
    }
}
