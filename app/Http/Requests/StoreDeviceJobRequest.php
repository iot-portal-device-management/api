<?php

namespace App\Http\Requests;

use App\Models\DeviceGroup;
use App\Models\DeviceJob;
use App\Models\SavedDeviceCommand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreDeviceJobRequest extends BaseFormRequest
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
                'required',
                'string',
                'max:255',
                Rule::unique(DeviceJob::getTableName(), 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                }),
            ],
            'deviceGroupId' => [
                'required',
                Rule::exists(DeviceGroup::getTableName(), 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                }),
            ],
            'savedDeviceCommandId' => [
                'required',
                Rule::exists(SavedDeviceCommand::getTableName(), 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                }),
            ],
        ];
    }
}
