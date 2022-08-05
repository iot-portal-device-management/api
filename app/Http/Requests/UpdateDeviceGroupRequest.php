<?php

namespace App\Http\Requests;

use App\Actions\DeviceGroup\FindDeviceGroupByIdAction;
use App\Rules\ExistsDeviceIdsForAuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDeviceGroupRequest extends BaseFormRequest
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
     * @param FindDeviceGroupByIdAction $findDeviceGroupByIdAction
     * @return array
     */
    public function rules(FindDeviceGroupByIdAction $findDeviceGroupByIdAction)
    {
        $existingDeviceGroup = $findDeviceGroupByIdAction->execute($this->route('deviceGroupId'));

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('device_groups', 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })->ignore($existingDeviceGroup->id),
            ],
            'deviceIds' => [
                'nullable',
                'array',
                new ExistsDeviceIdsForAuthUser,
            ],
        ];
    }
}
