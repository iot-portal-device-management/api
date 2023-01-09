<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Rules;

use App\Models\DeviceCategory;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ExistsDeviceCategoryIdForAuthUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, mixed $value): bool
    {
        return DeviceCategory::id($value)->userId(Auth::id())->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string|array
    {
        return 'The selected :attribute is invalid.';
    }
}
