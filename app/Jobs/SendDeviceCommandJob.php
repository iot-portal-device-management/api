<?php

namespace App\Jobs;

use App\Actions\Mqtt\PublishMqttToDeviceAction;
use App\Exceptions\DeviceTimeoutException;
use App\Models\DeviceCommand;
use App\Models\DeviceCommandErrorType;
use App\Models\DeviceCommandStatus;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use Throwable;

class SendDeviceCommandJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The DeviceCommand instance.
     *
     * @var DeviceCommand
     */
    protected DeviceCommand $deviceCommand;

    /**
     * The string instance.
     *
     * @var string
     */
    protected string $payload;

    /**
     * Create a new job instance.
     *
     * @param DeviceCommand $deviceCommand
     * @param string $payload
     */
    public function __construct(DeviceCommand $deviceCommand, string $payload)
    {
        $this->deviceCommand = $deviceCommand;
        $this->payload = $payload;
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

        $deviceCommand = $this->deviceCommand;

        $deviceCommand->update([
            'device_command_status_id' => DeviceCommandStatus::getStatus(DeviceCommandStatus::STATUS_PROCESSING)->id,
            'job_id' => $this->job->getJobId(),
            'started_at' => now(),
        ]);

        $publishMqttToDeviceAction->execute(
            $deviceCommand->deviceCommandType->device->id,
            $deviceCommand->deviceCommandType->method_name,
            $deviceCommand->id,
            $this->payload,
        );

        $deviceCommand->refresh();
        $startedAt = new Carbon($deviceCommand->started_at);

        while (!$deviceCommand->responded_at && $startedAt->diffInSeconds() <= 30) {
            sleep(1);
            $deviceCommand->refresh();
        }

        if (!$deviceCommand->responded_at) {
            throw new DeviceTimeoutException('Timeout waiting for device to respond.');
        }

        $deviceCommand->update([
            'device_command_status_id' => DeviceCommandStatus::getStatus(DeviceCommandStatus::STATUS_SUCCESSFUL)->id,
            'completed_at' => now(),
        ]);
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $now = now();
        $deviceCommand = $this->deviceCommand;
        $failedDeviceCommandStatusId = DeviceCommandStatus::getStatus(DeviceCommandStatus::STATUS_FAILED)->id;

        if ($exception instanceof ConnectingToBrokerFailedException) {
            $deviceCommand->update([
                'device_command_status_id' => $failedDeviceCommandStatusId,
                'device_command_error_type_id' => DeviceCommandErrorType::getType(DeviceCommandErrorType::TYPE_MQTT_BROKER_CONNECTION_REFUSED)->id,
                'failed_at' => $now,
            ]);
        } else if ($exception instanceof DeviceTimeoutException) {
            $deviceCommand->update([
                'device_command_status_id' => $failedDeviceCommandStatusId,
                'device_command_error_type_id' => DeviceCommandErrorType::getType(DeviceCommandErrorType::TYPE_DEVICE_TIMEOUT)->id,
                'failed_at' => $now,
            ]);
        } else {
            $deviceCommand->update([
                'device_command_status_id' => $failedDeviceCommandStatusId,
                'device_command_error_type_id' => DeviceCommandErrorType::getType(DeviceCommandErrorType::TYPE_OTHERS)->id,
                'failed_at' => $now,
            ]);
        }
    }
}
