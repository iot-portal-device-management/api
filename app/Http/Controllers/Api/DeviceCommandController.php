<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceCommand\FilterDataTableDeviceCommandsAction;
use App\Actions\DeviceCommandType\TriggerDeviceCommandAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\TriggerDeviceCommandRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class DeviceCommandController
 * @package App\Http\Controllers\Api
 */
class DeviceCommandController extends Controller
{
//    /**
//     * DeviceCommandController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:view,device')->only('index');
//    }

    /**
     * Return a listing of the device commands.
     *
     * @param Request $request
     * @param FilterDataTableDeviceCommandsAction $filterDataTableDeviceCommandsAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableDeviceCommandsAction $filterDataTableDeviceCommandsAction, string $deviceId): JsonResponse
    {
        $deviceCommands = $filterDataTableDeviceCommandsAction->execute($deviceId, $request->all());

        return $this->apiOk(['deviceCommands' => $deviceCommands]);
    }

    /**
     * Trigger device command.
     *
     * @param TriggerDeviceCommandRequest $request
     * @param TriggerDeviceCommandAction $triggerDeviceCommandAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function triggerDeviceCommand(TriggerDeviceCommandRequest $request, TriggerDeviceCommandAction $triggerDeviceCommandAction, string $deviceId): JsonResponse
    {
        $deviceCommand = $triggerDeviceCommandAction->execute($deviceId, $request->validated());

        return $this->apiOk(['deviceCommand' => $deviceCommand]);
    }
}
