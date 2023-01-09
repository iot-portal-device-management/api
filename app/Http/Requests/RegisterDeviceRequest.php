<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Requests;

use App\Rules\ExistsDeviceId;
use App\Rules\ExistsUserId;

class RegisterDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'userId' => [
                'required',
                new ExistsUserId,
            ],
            'deviceId' => [
                'nullable',
                new ExistsDeviceId,
            ],
        ];
    }
}
