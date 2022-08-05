<?php

namespace App\Http\Requests;

use App\Rules\ExistsDeviceGroupIdsForAuthUser;

class DestroySelectedDeviceGroupRequest extends BaseFormRequest
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
            'ids' => [
                'required',
                'array',
                new ExistsDeviceGroupIdsForAuthUser,
            ],
        ];
    }
}
