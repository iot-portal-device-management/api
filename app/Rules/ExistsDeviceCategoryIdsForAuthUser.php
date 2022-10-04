<?php

namespace App\Rules;

use App\Models\DeviceCategory;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ExistsDeviceCategoryIdsForAuthUser implements Rule
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
        if (isset($value) && $value) {
            return DeviceCategory::idIn($value)->userId(Auth::id())->count() >= count($value);
        }

        return false;
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
