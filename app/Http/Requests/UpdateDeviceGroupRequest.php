<?php

namespace App\Http\Requests;

use App\Actions\DeviceGroup\FindDeviceGroupByIdAction;
use App\Models\DeviceGroup;
use App\Rules\ExistsDeviceIdsForAuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDeviceGroupRequest extends FormRequest
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
     * @param FindDeviceGroupByIdAction $findDeviceGroupByIdAction
     * @return array
     */
    public function rules(FindDeviceGroupByIdAction $findDeviceGroupByIdAction): array
    {
        $oldDeviceGroupId = $findDeviceGroupByIdAction->execute($this->route('deviceGroupId'))->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(DeviceGroup::getTableName(), 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($oldDeviceGroupId),
            ],
            'deviceIds' => [
                'nullable',
                'array',
                new ExistsDeviceIdsForAuthUser,
            ],
        ];
    }
}
