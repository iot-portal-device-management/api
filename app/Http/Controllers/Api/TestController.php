<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $deviceTableName = Device::getTableName();

        $newDeviceCountByDate = User::find('cdc26291-c2c4-4b8d-a138-1bd5ab22653b')->devices()
            ->where($deviceTableName . '.created_at', '>=', Carbon::today()->subDays(10000))
            ->groupBy('date')
            ->groupBy('user_id')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(' . $deviceTableName . '.created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->keyBy('date');

        $lastSevenDaysInitialCount = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $lastSevenDaysInitialCount->put($date, [
                'date' => $date,
                'count' => 0,

            ]);
        }

        $lastSevenDaysInitialCount->merge($newDeviceCountByDate)->values()->toArray();
    }
}
