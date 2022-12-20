<?php

namespace App\Actions\Device;

use App\Exceptions\InvalidDeviceConnectionKeyException;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Arr;

class RegisterDeviceAction
{
    /**
     * @var FindDeviceByIdByUserIdAction
     */
    private FindDeviceByIdByUserIdAction $findDeviceByIdByUserIdAction;

    /**
     * @var CreateDeviceAction
     */
    private CreateDeviceAction $createDeviceAction;

    public function __construct(
        FindDeviceByIdByUserIdAction $findDeviceByIdByUserIdAction,
        CreateDeviceAction $createDeviceAction
    )
    {
        $this->findDeviceByIdByUserIdAction = $findDeviceByIdByUserIdAction;
        $this->createDeviceAction = $createDeviceAction;
    }

    public function execute(array $data): Device
    {
        if (isset($data['userId']) && isset($data['deviceConnectionKey'])) {
            $user = User::findOrFail($data['userId']);

            if ($user->validateDeviceConnectionKey($data['deviceConnectionKey'])) {
                if (isset($data['deviceId'])) {
                    return $this->findDeviceByIdByUserIdAction->execute(Arr::only($data, ['deviceId', 'userId']));
                } else {
                    return $this->createDeviceAction->execute(Arr::only($data, ['userId']));
                }
            }
        }

        throw new InvalidDeviceConnectionKeyException('Invalid device connection key.');
    }
}
