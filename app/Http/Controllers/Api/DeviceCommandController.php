<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceCommand\FilterDataTableDeviceCommandsAction;
use App\Http\Controllers\Controller;
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
     * Return a listing of the command histories.
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
}
