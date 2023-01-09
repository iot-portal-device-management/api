<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Requests;

use App\Actions\DeviceCategory\FindDeviceCategoryByIdAction;
use App\Models\DeviceCategory;
use App\Rules\ExistsDeviceIdsForAuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDeviceCategoryRequest extends FormRequest
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
        $oldDeviceCategoryId = $this->route('deviceCategory')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(DeviceCategory::getTableName(), 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($oldDeviceCategoryId),
            ],
            'deviceIds' => [
                'nullable',
                'array',
                new ExistsDeviceIdsForAuthUser,
            ],
        ];
    }
}
