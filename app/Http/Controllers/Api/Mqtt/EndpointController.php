<?php

namespace App\Http\Controllers\Api\Mqtt;

use App\Actions\Device\UpdateDeviceLastSeenToNowAction;
use App\Actions\Device\UpdateDeviceStatusToOfflineAction;
use App\Actions\Device\UpdateDeviceStatusToOnlineAction;
use App\Actions\Mqtt\CheckIfValidPortalMqttClientId;
use App\Actions\Mqtt\CheckIfValidPortalMqttCredentials;
use App\Actions\Mqtt\CheckIfValidPortalMqttTopics;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceEventType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class EndpointController
 * @package App\Http\Controllers\Api\Mqtt
 */
class EndpointController extends Controller
{
    /**
     * Parse the VerneMQ callback header and calls the respective handlers with the request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function mqttEndpoint(Request $request): JsonResponse
    {
        $verneMqHook = $request->header(config('vernemq.webhook_header', 'vernemq-hook'));
        Log::debug('[MQTT Received] ' . $verneMqHook . ', Request: ' . json_encode($request->except('password')));

        if ($verneMqHook) {
            if ($verneMqHook === config('vernemq.hooks.auth_on_register', 'auth_on_register')) {
                return $this->authOnRegister($request);
            } elseif ($verneMqHook === config('vernemq.hooks.auth_on_subscribe', 'auth_on_subscribe')) {
                return $this->authOnSubscribe($request);
            } elseif ($verneMqHook === config('vernemq.hooks.auth_on_publish', 'auth_on_publish')) {
                return $this->authOnPublish($request);
            } elseif ($verneMqHook === config('vernemq.hooks.on_client_offline', 'on_client_offline')) {
                return $this->onClientOffline($request);
            } elseif ($verneMqHook === config('vernemq.hooks.on_client_gone', 'on_client_gone')) {
                return $this->onClientGone($request);
            }
        }

        return $this->apiMqttBadRequest(['error' => 'Header vernemq-hook is invalid.']);
    }

    /**
     * Handle MQTT register request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function authOnRegister(Request $request): JsonResponse
    {
        $username = $request->username;
        $password = $request->password;
        $clientId = $request->client_id;

        // If the payload provided matches the portal MQTT server credentials, then it is from the backend portal, we
        // return OK if they are matched.
        if ((new CheckIfValidPortalMqttCredentials)->execute($username, $password, $clientId)) {
            return $this->apiMqttOk('ok');
        }

        // Else, we check if the MQTT message is from the device client and if the credentials match.
        $validator = Validator::make($request->all(), [
            'username' => 'required|same:client_id',
            'password' => 'required',
            'client_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiMqttBadRequest(['error' => $validator->getMessageBag()->toArray()]);
        }

        $device = Device::findOrFail($username);

        if ($device->validateMqttPassword($password)) {
            (new UpdateDeviceStatusToOnlineAction(new UpdateDeviceLastSeenToNowAction))->execute($device);
            return $this->apiMqttOk('ok');
        }

        return $this->apiMqttUnauthorized(['error' => 'Invalid username or password.']);
    }

    /**
     * Handle MQTT topic subscribe request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function authOnSubscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|same:client_id',
            'client_id' => 'required',
            'topics' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiMqttBadRequest(['error' => $validator->getMessageBag()->toArray()]);
        }

        $username = $request->username;

        $disallowedTopics = [];

        foreach ($request->topics as $topic) {
            // If the MQTT client is not subscribing to its own username's topic, we disallow that.
            if (!preg_match('/iotportal\/' . $username . '\//', $topic['topic'])) {
                $disallowedTopics[] = [
                    'topic' => $topic['topic'],
                    'qos' => 128
                ];
            }
        }

        $device = Device::findOrFail($username);
        (new UpdateDeviceStatusToOnlineAction(new UpdateDeviceLastSeenToNowAction))->execute($device);

        return $disallowedTopics
            ? $this->apiMqttOkWithDisallowedTopics('ok', $disallowedTopics)
            : $this->apiMqttOk('ok');
    }

    /**
     * Handle MQTT topic publish request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function authOnPublish(Request $request): JsonResponse
    {
        $username = $request->username;
        $clientId = $request->client_id;
        $topic = $request->topic;

        // If the payload provided matches the portal MQTT server credentials, then it is from the backend portal, we
        // return OK if they are matched.
        if ((new CheckIfValidPortalMqttTopics)->execute($username, $clientId, $topic)) {
            return $this->apiMqttOk('ok');
        }

        // Else, we check if the MQTT message is from the device client and if the credentials match.
        $validator = Validator::make($request->all(), [
            'username' => 'required|same:client_id',
            'client_id' => 'required',
            'topic' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiMqttBadRequest(['error' => $validator->getMessageBag()->toArray()]);
        }

        $payload = Helper::sanitisePayload(base64_decode($request->input('payload')));

        if (preg_match('/(devices|iotportal)\/([\w\-]+)\//', $topic, $deviceIdMatches)) {
            $extractedDeviceId = $deviceIdMatches[2];

            if ($username === $clientId && $clientId === $extractedDeviceId) {

                $device = Device::findOrFail($username);

                (new UpdateDeviceStatusToOnlineAction(new UpdateDeviceLastSeenToNowAction()))->execute($device);

                if (preg_match('/devices\/([\w\-]+)\/messages\/events/', $topic)) {
                    return $this->messagesEvents($device, $payload);
                } elseif (preg_match('/devices\/([\w\-]+)\/properties\/reported/', $topic)) {
                    return $this->propertiesReported($device, $payload);
                } elseif (preg_match('/iotportal\/([\w\-]+)\/methods\/res\/\?\$rid=([\d]+)/', $topic, $requestIdMatches)) {
                    return $this->updateResponse($device, $requestIdMatches[2]);
                }

                return $this->apiMqttBadRequest(['error' => 'Unsupported topic.']);
            }

            return $this->apiMqttBadRequest(['error' => 'username, client_id and deviceId in topic do not match']);
        }

        return $this->apiMqttBadRequest(['error' => 'Invalid deviceId in topic.']);
    }

    /**
     * Handle client offline callback and update online state.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function onClientOffline(Request $request): JsonResponse
    {
        // If the client_id is the backend portal's, we return OK.
        if ((new CheckIfValidPortalMqttClientId)->execute($request->client_id)) {
            return $this->apiMqttOk('ok');
        }

        // Else, it is from the device client and we update device status to offline.
        $device = Device::findOrFail($request->client_id);

        $success = (new UpdateDeviceStatusToOfflineAction(new UpdateDeviceLastSeenToNowAction))->execute($device);

        return $success
            ? $this->apiMqttOk('ok')
            : $this->apiMqttInternalServerError(['error' => 'Error updating device status.']);
    }

    /**
     * Handle client disconnected callback and update online state.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function onClientGone(Request $request): JsonResponse
    {
        // If the client_id is the backend portal's, we return OK.
        if ((new CheckIfValidPortalMqttClientId)->execute($request->client_id)) {
            return $this->apiMqttOk('ok');
        }

        // Else, it is from the device client and we update device status to offline.
        $device = Device::findOrFail($request->client_id);

        $success = (new UpdateDeviceStatusToOfflineAction(new UpdateDeviceLastSeenToNowAction))->execute($device);

        return $success
            ? $this->apiMqttOk('ok')
            : $this->apiMqttInternalServerError(['error' => 'Error updating device status.']);
    }

    /**
     * Handle message events from clients.
     *
     * @param Device $device
     * @param string|null $payload
     * @return JsonResponse
     */
    protected function messagesEvents(Device $device, ?string $payload): JsonResponse
    {
        Log::debug('[MQTT Message DeviceEventType] ' . $payload);

        $deviceEvent = $device->deviceEvents()->create([
            'raw_data' => Helper::isJson($payload) ? $payload : json_encode($payload),
            'device_event_type_id' => $device->deviceEventTypes()->getType(DeviceEventType::TYPE_TELEMETRY)->id,
        ]);

        $payload = json_decode($payload);

        if ($payload) {
            foreach ($payload as $key => $value) {
                switch ($key) {
                    case 'systemCpuPercent':
                        $device->deviceCpuStatistics()->create([
                            'cpu_usage_percentage' => $value,
                        ]);
                        break;
                    case 'containersCpuPercent':
                        $device->deviceContainerStatistics()->create([
                            'container_message' => json_encode($value),
                        ]);
                        break;
                    case 'availableMemory':
                        $device->deviceMemoryStatistics()->create([
                            'available_memory_in_bytes' => $value,
                        ]);
                        break;
                    case 'percentDiskUsed':
                        $device->deviceDiskStatistics()->create([
                            'disk_usage_percentage' => $value,
                        ]);
                        break;
                    case 'coreTempCelsius':
                        if ($value && $value !== "Unknown") {
                            $device->deviceCpuTemperatureStatistics()->create([
                                'temperature' => $value,
                            ]);
                        }
                        break;
                    case 'networkInformation':
                        $device->deviceNetworkStatistics()->create([
                            'network_message' => json_encode($value),
                        ]);
                        break;
                    default:
                        // not handled
                }
            }
        }

        return $deviceEvent->exists
            ? $this->apiMqttOk('ok')
            : $this->apiMqttInternalServerError(['error' => 'Error saving device event.']);
    }

    /**
     * Handle properties reported from clients.
     *
     * @param Device $device
     * @param string|null $payload
     * @return JsonResponse
     */
    protected function propertiesReported(Device $device, ?string $payload): JsonResponse
    {
        Log::debug('[MQTT Properties Reported] ' . $payload);

        $deviceEvent = $device->deviceEvents()->create([
            'raw_data' => Helper::isJson($payload) ? $payload : json_encode($payload),
            'device_event_type_id' => $device->deviceEventTypes()->getType(DeviceEventType::TYPE_PROPERTY)->id,
        ]);

        $payload = json_decode($payload);

        if ($payload) {
            foreach ($payload as $key => $value) {
                switch ($key) {
                    case 'totalPhysicalMemory':
                        $device->update(['total_memory' => $value]);
                        break;
                    case 'cpuId':
                        $device->update(['cpu' => $value]);
                        break;
                    case 'biosVendor':
                        $device->update(['bios_vendor' => $value]);
                        break;
                    case 'biosVersion':
                        $device->update(['bios_version' => $value]);
                        break;
                    case 'biosReleaseDate':
                        $device->update(['bios_release_date' => $value]);
                        break;
                    case 'systemManufacturer':
                        $device->update(['system_manufacturer' => $value]);
                        break;
                    case 'systemProductName':
                        $device->update(['system_product_name' => $value]);
                        break;
                    case 'osInformation':
                        $device->update(['os_information' => $value]);
                        break;
                    case 'diskInformation':
                        $device->update(['disk_information' => $value]);
                        break;
                    default:
                        // not handled
                }
            }
        }

        return $deviceEvent->exists
            ? $this->apiMqttOk('ok')
            : $this->apiMqttInternalServerError(['error' => 'Error saving device property.']);
    }

    /**
     * Update command response time for clients.
     *
     * @param Device $device
     * @param int $requestId
     * @return JsonResponse
     */
    protected function updateResponse(Device $device, int $requestId): JsonResponse
    {
        $success = $device->deviceCommands()->find($requestId)->update(['responded_at' => now()]);

        return $success
            ? $this->apiMqttOk('ok')
            : $this->apiMqttInternalServerError(['error' => 'Error updating device response time.']);
    }
}
