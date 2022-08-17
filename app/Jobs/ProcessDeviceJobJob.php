<?php

namespace App\Jobs;

use App\Exceptions\DeviceTimeoutException;
use App\Models\Device;
use App\Models\DeviceCommandErrorType;
use App\Models\DeviceCommandStatus;
use App\Models\DeviceJob;
use App\Models\DeviceJobErrorType;
use App\Models\DeviceJobStatus;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use Throwable;

class ProcessDeviceJobJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The DeviceJob instance.
     *
     * @var DeviceJob
     */
    protected DeviceJob $deviceJob;

    /**
     * The failedDeviceCommandStatusId instance.
     *
     * @var string
     */
    protected string $failedDeviceCommandStatusId;


    /**
     * Create a new job instance.
     *
     * @param DeviceJob $deviceJob
     */
    public function __construct(DeviceJob $deviceJob)
    {
        $this->deviceJob = $deviceJob;
        $this->failedDeviceCommandStatusId = DeviceCommandStatus::getStatus(DeviceCommandStatus::STATUS_FAILED)->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function handle()
    {
        $deviceJob = $this->deviceJob;
        $failedDeviceJobStatusId = $this->failedDeviceCommandStatusId;

        $deviceJob->update([
            'job_id' => $this->job->getJobId(),
            'device_job_status_id' => DeviceJobStatus::getStatus(DeviceJobStatus::STATUS_PROCESSING)->id,
            'started_at' => now(),
        ]);

        $payload = $deviceJob->savedDeviceCommand->payload;

        $sendDeviceCommandJobs = $deviceJob->deviceGroup->devices
            ->map(function (Device $device) use ($payload) {
                return $this->createSendDeviceCommandJob($device, $payload);
            })
            ->filter(function (?SendDeviceCommandJob $sendDeviceCommandJob) {
                return !is_null($sendDeviceCommandJob);
            })
            ->toArray();

        $jobBatch = Bus::batch($sendDeviceCommandJobs)
            ->then(function (Batch $batch) use ($deviceJob) {
                $deviceJob->update([
                    'device_job_status_id' => DeviceJobStatus::getStatus(DeviceJobStatus::STATUS_SUCCESSFUL)->id,
                    'completed_at' => now(),
                ]);
            })
            ->catch(function (Batch $batch, Throwable $exception) use ($deviceJob, $failedDeviceJobStatusId) {
                if ($exception instanceof ConnectingToBrokerFailedException) {
                    $deviceJob->update([
                        'device_job_error_type_id' => DeviceJobErrorType::getType(DeviceJobErrorType::TYPE_MQTT_BROKER_CONNECTION_REFUSED)->id,
                        'device_job_status_id' => $failedDeviceJobStatusId,
                        'failed_at' => now(),
                    ]);
                } else if ($exception instanceof DeviceTimeoutException) {
                    $deviceJob->update([
                        'device_job_error_type_id' => DeviceJobErrorType::getType(DeviceJobErrorType::TYPE_DEVICE_TIMEOUT)->id,
                        'device_job_status_id' => $failedDeviceJobStatusId,
                        'failed_at' => now(),
                    ]);
                } else {
                    $deviceJob->update([
                        'device_job_error_type_id' => DeviceJobErrorType::getType(DeviceJobErrorType::TYPE_OTHERS)->id,
                        'device_job_status_id' => $failedDeviceJobStatusId,
                        'failed_at' => now(),
                    ]);
                }
            })
            ->name($this->deviceJob->id)
            ->allowFailures()
            ->dispatch();

        $deviceJob->update([
            'job_batch_id' => $jobBatch->id,
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
        $this->deviceJob->update([
            'device_job_error_type_id' => DeviceCommandErrorType::getType(DeviceCommandErrorType::TYPE_OTHERS)->id,
            'device_job_status_id' => $this->failedDeviceCommandStatusId,
            'failed_at' => now(),
        ]);
    }

    public function createSendDeviceCommandJob(Device $device, string $payload): ?SendDeviceCommandJob
    {
        $deviceCommandTypeName = $this->deviceJob->savedDeviceCommand->device_command_type_name;

        $deviceCommandType = $device->deviceCommandTypes()->name($deviceCommandTypeName)->first();

        if ($deviceCommandType) {
            $deviceCommand = $deviceCommandType->deviceCommands()->create([
                'payload' => $payload ?? null,
                'device_job_id' => $this->deviceJob->id,
            ]);
        } else {
            $deviceCommand = $deviceCommandType->deviceCommands()->create([
                'payload' => $payload ?? null,
                'device_command_error_type_id' => DeviceCommandErrorType::getType(DeviceCommandErrorType::TYPE_DEVICE_COMMAND_TYPE_NOT_SUPPORTED)->id,
                'device_command_status_id' => $this->failedDeviceCommandStatusId,
                'device_job_id' => $this->deviceJob->id,
                'failed_at' => now(),
            ]);
        }

        return $deviceCommand->deviceCommandStatus->id === $this->failedDeviceCommandStatusId
            ? null
            : new SendDeviceCommandJob($deviceCommand, $payload);
    }
}
