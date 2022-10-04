<?php

namespace App\Http\Requests;

use App\Actions\Device\FindDeviceByIdAction;
use App\Rules\ExistsDeviceCategoryIdForAuthUser;
use App\Rules\UniqueDeviceNameExcludeOldForAuthUser;

class UpdateDeviceRequest extends FormRequest
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
     * @param FindDeviceByIdAction $findDeviceByIdAction
     * @return array
     */
    public function rules(FindDeviceByIdAction $findDeviceByIdAction): array
    {
        $oldDeviceId = $findDeviceByIdAction->execute($this->route('deviceId'))->id;

        return [
            'name' => [
                'nullable',
                'string',
                'max:255',
                new UniqueDeviceNameExcludeOldForAuthUser($oldDeviceId),
            ],
            'deviceCategoryId' => [
                'nullable',
                new ExistsDeviceCategoryIdForAuthUser,
            ],
        ];
    }
}
