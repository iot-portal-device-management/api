<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Actions\DeviceCommandType;

use App\Actions\DeviceCommand\CreateDeviceCommandForDeviceCommandTypeAction;
use App\Actions\DeviceCommand\MarkDeviceCommandAsCompletedAction;
use App\Actions\Mqtt\PublishMqttToDeviceAction;
use App\Models\DeviceCommand;
use App\Models\DeviceCommandStatus;
use Illuminate\Support\Arr;

class TriggerDeviceCommandAction
{
    /**
     * @var FindDeviceCommandTypeByNameByDeviceIdAction
     */
    private FindDeviceCommandTypeByNameByDeviceIdAction $findDeviceCommandTypeByNameByDeviceIdAction;

    /**
     * @var CreateDeviceCommandForDeviceCommandTypeAction
     */
    private CreateDeviceCommandForDeviceCommandTypeAction $createDeviceCommandForDeviceCommandTypeAction;

    /**
     * @var PublishMqttToDeviceAction
     */
    private PublishMqttToDeviceAction $publishMqttToDeviceAction;

    /**
     * @var MarkDeviceCommandAsCompletedAction
     */
    private MarkDeviceCommandAsCompletedAction $markDeviceCommandAsCompletedAction;

    public function __construct(
        FindDeviceCommandTypeByNameByDeviceIdAction $findDeviceCommandTypeByNameByDeviceIdAction,
        CreateDeviceCommandForDeviceCommandTypeAction $createDeviceCommandForDeviceCommandTypeAction,
        PublishMqttToDeviceAction $publishMqttToDeviceAction,
        MarkDeviceCommandAsCompletedAction $markDeviceCommandAsCompletedAction
    )
    {
        $this->findDeviceCommandTypeByNameByDeviceIdAction = $findDeviceCommandTypeByNameByDeviceIdAction;
        $this->createDeviceCommandForDeviceCommandTypeAction = $createDeviceCommandForDeviceCommandTypeAction;
        $this->publishMqttToDeviceAction = $publishMqttToDeviceAction;
        $this->markDeviceCommandAsCompletedAction = $markDeviceCommandAsCompletedAction;
    }

    public function execute(array $data): DeviceCommand
    {
        $deviceCommandType = $this->findDeviceCommandTypeByNameByDeviceIdAction->execute(Arr::only($data, [
            'deviceCommandTypeName',
            'deviceId',
        ]));

        $payloadString = json_encode($data['payload'] ?? null);

        $deviceCommand = $this->createDeviceCommandForDeviceCommandTypeAction->execute([
            'payload' => $payloadString,
            'started_at' => now(),
            'device_command_status_id' => DeviceCommandStatus::getStatus(DeviceCommandStatus::STATUS_PROCESSING)->id,
            'device_command_type_id' => $deviceCommandType->id,
        ]);

        $this->publishMqttToDeviceAction->execute(
            $data['deviceId'],
            $deviceCommandType->method_name,
            $deviceCommand->id,
            $payloadString
        );

        $this->markDeviceCommandAsCompletedAction->execute($deviceCommand);

        return $deviceCommand;
    }
}
