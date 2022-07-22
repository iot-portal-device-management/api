<?php

namespace App\Actions\Commands;

use App\Actions\CommandHistories\CreateCommandHistoryForCommandAction;
use App\Actions\CommandHistories\MarkCommandHistoryAsCompletedAction;
use App\Actions\Mqtt\PublishMqttToDeviceAction;
use App\Models\CommandHistory;

class TriggerCommandAction
{
    private FindCommandByNameForDeviceAction $findCommandByNameForDeviceAction;

    /**
     * @var CreateCommandHistoryForCommandAction
     */
    private CreateCommandHistoryForCommandAction $createCommandHistoryForCommandAction;

    /**
     * @var PublishMqttToDeviceAction
     */
    private PublishMqttToDeviceAction $publishMqttToDeviceAction;

    /**
     * @var MarkCommandHistoryAsCompletedAction
     */
    private MarkCommandHistoryAsCompletedAction $markCommandHistoryAsCompletedAction;

    public function __construct(FindCommandByNameForDeviceAction $findCommandByNameForDeviceAction,
                                CreateCommandHistoryForCommandAction $createCommandHistoryForCommandAction,
                                PublishMqttToDeviceAction $publishMqttToDeviceAction,
                                MarkCommandHistoryAsCompletedAction $markCommandHistoryAsCompletedAction)
    {
        $this->findCommandByNameForDeviceAction = $findCommandByNameForDeviceAction;
        $this->createCommandHistoryForCommandAction = $createCommandHistoryForCommandAction;
        $this->publishMqttToDeviceAction = $publishMqttToDeviceAction;
        $this->markCommandHistoryAsCompletedAction = $markCommandHistoryAsCompletedAction;
    }

    public function execute(string $deviceId, array $data): CommandHistory
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
