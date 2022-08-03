<?php

namespace App\Actions\DeviceCommandType;

use App\Actions\DeviceCommand\CreateDeviceCommandForDeviceCommandTypeAction;
use App\Actions\DeviceCommand\MarkCommandHistoryAsCompletedAction;
use App\Actions\Mqtt\PublishMqttToDeviceAction;
use App\Models\DeviceCommand;

class TriggerCommandAction
{
    private FindDeviceCommandTypeByNameForDeviceAction $findCommandByNameForDeviceAction;

    /**
     * @var CreateDeviceCommandForDeviceCommandTypeAction
     */
    private CreateDeviceCommandForDeviceCommandTypeAction $createCommandHistoryForCommandAction;

    /**
     * @var PublishMqttToDeviceAction
     */
    private PublishMqttToDeviceAction $publishMqttToDeviceAction;

    /**
     * @var MarkCommandHistoryAsCompletedAction
     */
    private MarkCommandHistoryAsCompletedAction $markCommandHistoryAsCompletedAction;

    public function __construct(FindDeviceCommandTypeByNameForDeviceAction $findCommandByNameForDeviceAction,
                                CreateDeviceCommandForDeviceCommandTypeAction $createCommandHistoryForCommandAction,
                                PublishMqttToDeviceAction $publishMqttToDeviceAction,
                                MarkCommandHistoryAsCompletedAction $markCommandHistoryAsCompletedAction)
    {
        $this->findCommandByNameForDeviceAction = $findCommandByNameForDeviceAction;
        $this->createCommandHistoryForCommandAction = $createCommandHistoryForCommandAction;
        $this->publishMqttToDeviceAction = $publishMqttToDeviceAction;
        $this->markCommandHistoryAsCompletedAction = $markCommandHistoryAsCompletedAction;
    }

    public function execute(string $deviceId, array $data): DeviceCommand
    {
        $command = $this->findCommandByNameForDeviceAction->execute($deviceId, $data['command']);

        $payloadJson = json_encode($data['payload']);

        $commandHistory = $this->createCommandHistoryForCommandAction->execute($command, [
            'payload' => $payloadJson,
            'started_at' => now(),
        ]);

        $this->publishMqttToDeviceAction->execute($deviceId, $command->method_name, $commandHistory->id, $payloadJson);

        $this->markCommandHistoryAsCompletedAction->execute($commandHistory);

        return $commandHistory;
    }
}
