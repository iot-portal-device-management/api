<?php

namespace App\Http\Requests;

use App\Actions\Device\FindDeviceByIdAction;
use App\Models\DeviceCommandType;
use Illuminate\Validation\Rule;

class TriggerDeviceCommandRequest extends FormRequest
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
        $deviceId = $findDeviceByIdAction->execute($this->route('deviceId'))->id;

        return [
            'deviceCommandTypeName' => [
                'required',
                'string',
                Rule::exists(DeviceCommandType::getTableName(), 'name')->where(function ($query) use ($deviceId) {
                    return $query->where('device_id', $deviceId);
                }),
            ],
            'payload' => [
                'nullable',
            ],
        ];
    }
}
