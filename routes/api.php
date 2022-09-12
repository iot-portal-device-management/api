<?php

use App\Http\Controllers\Api\DeviceCategoryController;
use App\Http\Controllers\Api\DeviceCommandController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceEventController;
use App\Http\Controllers\Api\DeviceGroupController;
use App\Http\Controllers\Api\DeviceJobController;
use App\Http\Controllers\Api\DeviceMetricController;
use App\Http\Controllers\Api\SavedDeviceCommandController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

require __DIR__ . '/api_auth.php';


//Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/111', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function () {
    // LOGGED IN USER
    Route::get('/user', [UserController::class, 'user']);


    // USER STATISTICS
    Route::get('/statistics', [StatisticController::class, 'showStatistics']);


    // DEVICES
    Route::get('/devices', [DeviceController::class, 'index']);

    Route::post('/devices', [DeviceController::class, 'store']);

    Route::get('/devices/{deviceId}', [DeviceController::class, 'show'])
        ->whereUuid('deviceId');

    Route::match(['put', 'patch'], '/devices/{deviceId}', [DeviceController::class, 'update'])
        ->whereUuid('deviceId');

    Route::delete('/devices', [DeviceController::class, 'destroySelected']);


    // DEVICE GROUPS
    Route::get('/device/groups/options', [DeviceGroupController::class, 'options']);

    Route::get('/device/groups/{deviceGroupId}/devices', [DeviceGroupController::class, 'deviceGroupDevicesIndex'])
        ->whereUuid('deviceGroupId');

    Route::get('/device/groups', [DeviceGroupController::class, 'index']);

    Route::post('/device/groups', [DeviceGroupController::class, 'store']);

    Route::get('/device/groups/{deviceGroupId}', [DeviceGroupController::class, 'show'])
        ->whereUuid('deviceGroupId');

    Route::match(['put', 'patch'], '/device/groups/{deviceGroupId}', [DeviceGroupController::class, 'update'])
        ->whereUuid('deviceGroupId');

    Route::delete('/device/groups', [DeviceGroupController::class, 'destroySelected']);


    // DEVICE CATEGORIES
    Route::get('/device/categories/options', [DeviceCategoryController::class, 'options']);

    Route::get('/device/categories/{deviceCategoryId}/devices', [DeviceCategoryController::class, 'deviceCategoryDevicesIndex'])
        ->whereUuid('deviceCategoryId');

    Route::get('/device/categories', [DeviceCategoryController::class, 'index']);

    Route::post('/device/categories', [DeviceCategoryController::class, 'store']);

    Route::get('/device/categories/{deviceCategoryId}', [DeviceCategoryController::class, 'show'])
        ->whereUuid('deviceCategoryId');

    Route::match(['put', 'patch'], '/device/categories/{deviceCategoryId}', [DeviceCategoryController::class, 'update'])
        ->whereUuid('deviceCategoryId');

    Route::delete('/device/categories', [DeviceCategoryController::class, 'destroySelected']);


    // DEVICE JOBS
    Route::get('/device/jobs/{deviceJobId}/progressStatus', [DeviceJobController::class, 'showProgressStatus'])
        ->whereUuid('deviceJobId');

    Route::get('/device/jobs/{deviceJobId}/deviceCommands', [DeviceJobController::class, 'deviceJobDeviceCommandsIndex'])
        ->whereUuid('deviceJobId');

    Route::get('/device/jobs', [DeviceJobController::class, 'index']);

    Route::post('/device/jobs', [DeviceJobController::class, 'store']);

    Route::get('/device/jobs/{deviceJobId}', [DeviceJobController::class, 'show'])
        ->whereUuid('deviceJobId');


    // SAVED DEVICE COMMANDS
    Route::get('/device/commands/saved/options', [SavedDeviceCommandController::class, 'options']);

    Route::get('/device/commands/saved', [SavedDeviceCommandController::class, 'index']);

    Route::post('/device/commands/saved', [SavedDeviceCommandController::class, 'store']);

    Route::get('/device/commands/saved/{savedDeviceCommandId}', [SavedDeviceCommandController::class, 'show'])
        ->whereUuid('savedDeviceCommandId');

    Route::delete('/device/commands/saved', [SavedDeviceCommandController::class, 'destroySelected']);


    // DEVICE METRICS AKA. DEVICE METRICS CHARTS
    Route::get('/devices/{deviceId}/metrics/cpu/temperatures', [DeviceMetricController::class, 'cpuTemperatures'])
        ->whereUuid('deviceId');

    Route::get('/devices/{deviceId}/metrics/cpu/usages', [DeviceMetricController::class, 'cpuUsages'])
        ->whereUuid('deviceId');

    Route::get('/devices/{deviceId}/metrics/disk/usages', [DeviceMetricController::class, 'diskUsages'])
        ->whereUuid('deviceId');

    Route::get('/devices/{deviceId}/metrics/memory/availables', [DeviceMetricController::class, 'memoryAvailables'])
        ->whereUuid('deviceId');


    // DEVICE OTA COMMAND TRIGGER ENDPOINT
    Route::post('/devices/{deviceId}/triggerDeviceCommand', [DeviceCommandController::class, 'triggerDeviceCommand'])
        ->whereUuid('deviceId');


    // DEVICE COMMANDS
    Route::get('/devices/{deviceId}/deviceCommands', [DeviceCommandController::class, 'index'])
        ->whereUuid('deviceId');


    // DEVICE EVENTS
    Route::get('/devices/{deviceId}/deviceEvents', [DeviceEventController::class, 'index'])
        ->whereUuid('deviceId');

});

Route::get('/test', [TestController::class, 'index']);


