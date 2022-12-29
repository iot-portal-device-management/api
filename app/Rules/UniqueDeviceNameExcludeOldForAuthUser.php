<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Rules;

use App\Models\Device;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UniqueDeviceNameExcludeOldForAuthUser implements Rule
{
    private string $oldDeviceId;

    /**
     * Create a new rule instance.
     *
     * @param $oldDeviceId
     */
    public function __construct($oldDeviceId)
    {
        $this->oldDeviceId = $oldDeviceId;

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
        return Device::name($value)
                ->excludeId($this->oldDeviceId)
                ->userId(Auth::id())
                ->count() <= 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string|array
    {
        return 'The :attribute has already been taken.';
    }
}
