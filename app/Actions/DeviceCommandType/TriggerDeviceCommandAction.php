<?php

namespace App\Actions\DeviceCommandType;

use App\Actions\DeviceCommand\CreateDeviceCommandForDeviceCommandTypeAction;
use App\Actions\DeviceCommand\MarkDeviceCommandAsCompletedAction;
use App\Actions\Mqtt\PublishMqttToDeviceAction;
use App\Models\DeviceCommand;

class TriggerDeviceCommandAction
{
    private FindDeviceCommandTypeByNameForDeviceAction $findDeviceCommandTypeByNameForDeviceAction;

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

    public function __construct(FindDeviceCommandTypeByNameForDeviceAction $findDeviceCommandTypeByNameForDeviceAction,
                                CreateDeviceCommandForDeviceCommandTypeAction $createDeviceCommandForDeviceCommandTypeAction,
                                PublishMqttToDeviceAction $publishMqttToDeviceAction,
                                MarkDeviceCommandAsCompletedAction $markDeviceCommandAsCompletedAction)
    {
        $this->findDeviceCommandTypeByNameForDeviceAction = $findDeviceCommandTypeByNameForDeviceAction;
        $this->createDeviceCommandForDeviceCommandTypeAction = $createDeviceCommandForDeviceCommandTypeAction;
        $this->publishMqttToDeviceAction = $publishMqttToDeviceAction;
        $this->markDeviceCommandAsCompletedAction = $markDeviceCommandAsCompletedAction;
    }

    public function execute(string $deviceId, array $data): DeviceCommand
    {
        $deviceCommandType = $this->findDeviceCommandTypeByNameForDeviceAction->execute($deviceId, $data['deviceCommandTypeName']);

        $payloadJson = json_encode($data['payload']);

        $deviceCommand = $this->createDeviceCommandForDeviceCommandTypeAction->execute([
            'payload' => $payloadJson,
            'started_at' => now(),
            'device_command_type_id' => $deviceCommandType->id,
        ]);

        $this->publishMqttToDeviceAction->execute($deviceId, $deviceCommandType->method_name, $deviceCommand->id, $payloadJson);

        $this->markDeviceCommandAsCompletedAction->execute($deviceCommand);

        return $deviceCommand;
    }
}
