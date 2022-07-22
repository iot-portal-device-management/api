<?php

namespace App\Http\Requests;

use App\Rules\ExistsDeviceCategoryIdForAuthUser;
use App\Rules\UniqueDeviceNameExcludeOldForAuthUser;

class UpdateDeviceRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'nullable',
                'string',
                'max:255',
                new UniqueDeviceNameExcludeOldForAuthUser($this->route('id')),
            ],
            'deviceCategory' => [
                'nullable',
                new ExistsDeviceCategoryIdForAuthUser,
            ],
        ];
    }
}
