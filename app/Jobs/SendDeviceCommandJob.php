<?php

namespace App\Jobs;

use App\Actions\Mqtt\PublishMqttToDeviceAction;
use App\Exceptions\DeviceTimeoutException;
use App\Models\DeviceCommand;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDeviceCommandJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The DeviceCommand instance.
     *
     * @var DeviceCommand
     */
    protected DeviceCommand $commandHistory;

    /**
     * The string instance.
     *
     * @var string
     */
    protected string $payloadJson;

    /**
     * Create a new job instance.
     *
     * @param DeviceCommand $commandHistory
     * @param string $payloadJson
     */
    public function __construct(DeviceCommand $commandHistory, string $payloadJson)
    {
        $this->commandHistory = $commandHistory;
        $this->payloadJson = $payloadJson;
    }

    /**
     * @return DeviceCommand
     */
    public function getCommandHistory(): DeviceCommand
    {
        return $this->commandHistory;
    }

    /**
     * Execute the job.
     *
     * @param PublishMqttToDeviceAction $publishMqttToDeviceAction
     * @return void
     * @throws DeviceTimeoutException
     */
    public function handle(PublishMqttToDeviceAction $publishMqttToDeviceAction)
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $this->commandHistory->update([
            'started_at' => now(),
        ]);

        $publishMqttToDeviceAction->execute($this->commandHistory->command->device->unique_id, $this->commandHistory->command->method_name, $this->commandHistory->id, $this->payloadJson);

        $this->commandHistory->refresh();
        $startedAt = new Carbon($this->commandHistory->started_at);
        while (!$this->commandHistory->responded_at && $startedAt->diffInSeconds() <= 30) {
            sleep(1);
            $this->commandHistory->refresh();
        }

        if (!$this->commandHistory->responded_at) {
            throw new DeviceTimeoutException('Timeout waiting for device to respond.');
        }
    }
}
