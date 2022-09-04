<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * @return JsonResponse
     */
    public function index(Request $request)
    {

    }
}
