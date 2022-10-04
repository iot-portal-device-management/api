<?php

namespace App\Http\Requests;

use App\Rules\ExistsDeviceCategoryIdForAuthUser;
use App\Rules\UniqueDeviceNameForAuthUser;

class StoreDeviceRequest extends FormRequest
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
                new UniqueDeviceNameForAuthUser,
            ],
            'deviceCategoryId' => [
                'required',
                new ExistsDeviceCategoryIdForAuthUser,
            ],
        ];
    }
}
