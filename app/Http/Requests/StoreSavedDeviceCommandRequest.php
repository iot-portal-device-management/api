<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Requests;

use App\Models\SavedDeviceCommand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreSavedDeviceCommandRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(SavedDeviceCommand::getTableName(), 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'deviceCommandTypeName' => 'required|string|max:255',
            'payload' => 'nullable',
        ];
    }
}
