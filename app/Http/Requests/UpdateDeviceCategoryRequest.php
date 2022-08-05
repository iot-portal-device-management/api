<?php

namespace App\Http\Requests;

use App\Actions\DeviceCategory\FindDeviceCategoryByIdAction;
use App\Rules\ExistsDeviceIdsForAuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDeviceCategoryRequest extends BaseFormRequest
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
     * @param FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction
     * @return array
     */
    public function rules(FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction)
    {
        $existingDeviceCategory = $findDeviceCategoryByIdAction->execute($this->route('deviceCategoryId'));

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('device_categories', 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })->ignore($existingDeviceCategory->id),
            ],
            'deviceIds' => [
                'nullable',
                'array',
                new ExistsDeviceIdsForAuthUser,
            ],
        ];
    }
}
