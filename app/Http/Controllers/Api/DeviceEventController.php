<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceEvent\FilterDataTableDeviceEventsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class DeviceEventController
 * @package App\Http\Controllers\Api
 */
class DeviceEventController extends Controller
{
//    /**
//     * DeviceEventController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:view,device')->only('index');
//    }

    /**
     * Return a listing of the device events.
     *
     * @param Request $request
     * @param FilterDataTableDeviceEventsAction $filterDataTableDeviceEventsAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableDeviceEventsAction $filterDataTableDeviceEventsAction, string $deviceId): JsonResponse
    {
        $deviceEvents = $filterDataTableDeviceEventsAction->execute($deviceId, $request->all());

        return $this->apiOk(['deviceEvents' => $deviceEvents]);
    }
}
