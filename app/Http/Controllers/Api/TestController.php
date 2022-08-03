<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceGroup\CreateDeviceGroupAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TestController
 * @package App\Http\Controllers\Api
 */
class TestController extends Controller
{
//    /**
//     * DeviceGroupController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:viewAny,App\Models\DeviceGroup')->only(['index', 'options']);
//        $this->middleware('can:create,App\Models\DeviceGroup')->only('store');
//        $this->middleware('can:update,deviceGroup')->only('update');
//        $this->middleware('can:deleteMany,App\Models\DeviceGroup')->only('destroySelected');
//    }

    /**
     * Return a listing of the device group.
     *
     * @param Request $request
     * @param CreateDeviceGroupAction $createDeviceGroupAction
     * @return JsonResponse
     */
    public function index(Request $request, CreateDeviceGroupAction $createDeviceGroupAction): JsonResponse
    {
        $createDeviceGroupAction->execute(User::find('83dd38d7-e000-4d0a-b6c0-a42de846e4b5'), [
            'deviceIds' => [
                '6af26551-867c-4be0-89fe-38f781705994',
                '95b70786-f8a4-49ba-b994-449128d5ca0b',
            ]
        ]);

//        return $this->apiOk(['deviceGroup' => $deviceGroup]);
    }
}
