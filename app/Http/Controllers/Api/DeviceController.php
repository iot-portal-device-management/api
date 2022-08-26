<?php

namespace App\Http\Controllers\Api;

use App\Actions\Device\CreateDeviceAction;
use App\Actions\Device\DeleteDevicesAction;
use App\Actions\Device\FilterDataTableDevicesAction;
use App\Actions\Device\FindDeviceByIdAction;
use App\Actions\Device\RegisterDeviceAction;
use App\Actions\Device\UpdateDeviceAction;
use App\Exceptions\InvalidDeviceConnectionKeyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedDevicesRequest;
use App\Http\Requests\RegisterDeviceRequest;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Http\Requests\ValidateDeviceFieldsRequest;
use App\Http\Resources\DeviceCollectionPagination;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceController
 * @package App\Http\Controllers\Api
 */
class DeviceController extends Controller
{
//    /**
//     * DeviceController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:viewAny,App\Models\Device')->only('index');
//        $this->middleware('can:create,App\Models\Device')->only('store');
//        $this->middleware('can:update,device')->only('update');
//        $this->middleware('can:deleteMany,App\Models\Device')->only('destroySelected');
//        $this->middleware('can:triggerCommand,device')->only('commands');
//    }

    /**
     * Return a listing of the devices.
     *
     * @param Request $request
     * @param FilterDataTableDevicesAction $filterDataTableDevicesAction
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableDevicesAction $filterDataTableDevicesAction)
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $devices = $filterDataTableDevicesAction->execute($data);

        return $this->apiOk(['devices' => new DeviceCollectionPagination($devices)]);
    }

    /**
     * Store a newly created device in storage.
     *
     * @param StoreDeviceRequest $request
     * @param CreateDeviceAction $createDeviceAction
     * @return JsonResponse
     */
    public function store(StoreDeviceRequest $request, CreateDeviceAction $createDeviceAction): JsonResponse
    {
        $device = $createDeviceAction->execute($request->user(), $request->validated());

        return $this->apiOk(['device' => new DeviceResource($device)]);
    }

    /**
     * Return the specified device.
     *
     * @param FindDeviceByIdAction $findDeviceByIdAction
     * @param string $deviceId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(FindDeviceByIdAction $findDeviceByIdAction, string $deviceId): JsonResponse
    {
        $device = $findDeviceByIdAction->execute($deviceId);

        $this->authorize('view', $device);

        return $this->apiOk(['device' => new DeviceResource($device)]);
    }

    /**
     * Update the specified device in storage.
     *
     * @param UpdateDeviceRequest $request
     * @param UpdateDeviceAction $updateDeviceAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function update(UpdateDeviceRequest $request, UpdateDeviceAction $updateDeviceAction, string $deviceId): JsonResponse
    {
        $success = $updateDeviceAction->execute($deviceId, $request->validated());

        return $success
            ? $this->apiOk(['device' => new DeviceResource(Device::id($deviceId)->with('deviceCategory:id,name', 'deviceStatus:id,name')->firstOrFail())])
            : $this->apiInternalServerError('Failed to update device.');
    }

    /**
     * Remove the specified devices from storage.
     *
     * @param DestroySelectedDevicesRequest $request
     * @param DeleteDevicesAction $deleteDevicesAction
     * @return JsonResponse
     */
    public function destroySelected(DestroySelectedDevicesRequest $request, DeleteDevicesAction $deleteDevicesAction): JsonResponse
    {
        $success = $deleteDevicesAction->execute($request->ids);

        return $success
            ? $this->apiOk()
            : $this->apiInternalServerError('Failed to delete devices');
    }

    /**
     * Validate device selection.
     *
     * @param ValidateDeviceFieldsRequest $request
     * @return JsonResponse
     */
    public function validateField(ValidateDeviceFieldsRequest $request): JsonResponse
    {
        return $this->apiOk();
    }

    /**
     * Handle device registration request from client devices.
     *
     * @param RegisterDeviceRequest $request
     * @param RegisterDeviceAction $registerDeviceAction
     * @return JsonResponse
     * @throws InvalidDeviceConnectionKeyException
     */
    public function register(RegisterDeviceRequest $request, RegisterDeviceAction $registerDeviceAction): JsonResponse
    {
        $device = $registerDeviceAction->execute($request->validated(), $request->bearerToken());

//        TODO: Possible refactoring, remove if statement since it is not necessary.
        if ($device) {
            return $this->apiOk([
                'mqttEndpoint' => config('mqttclient.connections.default.external_endpoint'),
                'device' => $device
            ]);
        }

        return $this->apiBadRequest('Invalid device_connection_key.');
    }
}
