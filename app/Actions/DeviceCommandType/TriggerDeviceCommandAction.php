<?php

namespace App\Actions\DeviceCommandType;

use App\Actions\DeviceCommand\CreateDeviceCommandForDeviceCommandTypeAction;
use App\Actions\DeviceCommand\MarkDeviceCommandAsCompletedAction;
use App\Actions\Mqtt\PublishMqttToDeviceAction;
use App\Models\DeviceCommand;
use App\Models\DeviceCommandStatus;

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

        $payloadString = json_encode($data['payload']);

        $deviceCommand = $this->createDeviceCommandForDeviceCommandTypeAction->execute([
            'payload' => $payloadString,
            'started_at' => now(),
            'device_command_status_id' => DeviceCommandStatus::getProcessing()->id,
        ]);

        $this->publishMqttToDeviceAction->execute($deviceId, $deviceCommandType->method_name, $deviceCommand->id, $payloadString);

        $this->markDeviceCommandAsCompletedAction->execute($deviceCommand);

        return $deviceCommand;
    }
}
