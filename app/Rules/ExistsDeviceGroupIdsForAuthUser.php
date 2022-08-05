<?php

namespace App\Rules;

use App\Models\DeviceGroup;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ExistsDeviceGroupIdsForAuthUser implements Rule
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
    public function passes($attribute, $value)
    {
        if (isset($value) && $value) {
            return DeviceGroup::idIn($value)->userId(Auth::user()->id)->count() >= count($value);
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}
