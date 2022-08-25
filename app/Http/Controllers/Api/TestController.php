<?php

namespace App\Http\Controllers\Api;

use App\Actions\DataTable\FilterDataTableAction;
use App\Http\Controllers\Controller;
use App\Models\DeviceCommand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
    public function index(Request $request): JsonResponse
    {

        $data = App::makeWith(FilterDataTableAction::class, [
            'query' => 'fffff',
            'sortModel' => [],
            'filterModel' => [],
        ]);

        dd($data);

        DB::connection()->enableQueryLog();

        $query = DeviceCommand::query();

//        $query->joinRelationship('deviceCommandType.device', [
//            'device' => function ($join) {
//                $join->where('devices.name', 'ILIKE', "%0%");
//            }
//        ]);


        $query->powerJoinWhereHas('deviceCommandType');
//
//        $query->powerJoinWhereHas('deviceCommandType.device', function ($join) {
//            $join->where('devices.name', 'ILIKE', "%0%");
//        });

//       dd( $query->get());

//        $query->orderByPowerJoins('deviceCommandType.name');





//        $query = DeviceCommand::deviceJobId('f3b60119-ab31-4c9c-992e-227343d15312')->with(
//            'deviceCommandStatus',
//            'deviceCommandType.device.deviceCategory',
//            'deviceCommandType.device.deviceStatus'
//        );

//        $query
//            ->leftJoin('device_command_types', 'device_command_types.id', '=', 'device_commands.device_command_type_id')
//            ->leftJoin('devices', 'devices.id', '=', 'device_command_types.device_id')
//            ->leftJoin('device_categories', 'device_categories.id', '=', 'devices.device_category_id')
//            ->orderBy('device_categories.name', 'desc')
//            ->select('device_commands.*');
//
//        $query->whereHas('deviceCommandType.device.deviceCategory', function ($query) {
//            $query->where('name', 'ILIKE', "%ROUTER%");
//        });


//        $query = DeviceCommand::deviceJobId('f3b60119-ab31-4c9c-992e-227343d15312')->with(
//            'deviceCommandStatus',
//            'deviceCommandType.device.deviceCategory',
//            'deviceCommandType.device.deviceStatus'
//        );

//        $query->orderBy(function (QueryBuilder $query) {
//            $query->select('name')
//                ->from('device_categories')
//                ->from('devices')
//                ->whereColumn('device_categories.id', 'devices.device_category_id')
//                ->whereColumn('devices.id', 'device_command_types.device_id')
//                ->whereColumn('device_command_types.id', 'device_commands.device_command_type_id')
//                ->limit(1);
//
//        }, 'desc');
//
//
        $results = $query->get();

        $queries = DB::getQueryLog();

        dd($queries);
    }
}
