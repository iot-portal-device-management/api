<?php

use App\Http\Controllers\Api\DeviceCategoryController;
use App\Http\Controllers\Api\DeviceCommandController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceEventController;
use App\Http\Controllers\Api\DeviceGroupController;
use App\Http\Controllers\Api\DeviceMetricController;
use App\Http\Controllers\Api\SavedDeviceCommandController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__ . '/api_auth.php';


// Device
Route::get('/devices', [DeviceController::class, 'index']);

Route::post('/devices', [DeviceController::class, 'store']);

Route::get('/devices/{deviceId}', [DeviceController::class, 'show'])
    ->whereUuid('deviceId');

Route::match(['put', 'patch'], '/devices/{deviceId}', [DeviceController::class, 'update'])
    ->whereUuid('deviceId');

Route::delete('/devices', [DeviceController::class, 'destroySelected']);


// Device Groups
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


// Device Categories
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


// Saved Device Commands
Route::get('/device/commands/saved/options', [SavedDeviceCommandController::class, 'options']);

Route::get('/device/commands/saved', [SavedDeviceCommandController::class, 'index']);

Route::post('/device/commands/saved', [SavedDeviceCommandController::class, 'store']);

Route::get('/device/commands/saved/{savedDeviceCommandId}', [SavedDeviceCommandController::class, 'show'])
    ->whereUuid('savedDeviceCommandId');

Route::delete('/device/commands/saved', [SavedDeviceCommandController::class, 'destroySelected']);


// Device OTA Command trigger endpoint
Route::post('/devices/{deviceId}/triggerDeviceCommand', [DeviceCommandController::class, 'triggerDeviceCommand'])
    ->whereUuid('deviceId');


// Device Metrics aka. Device metrics charts
Route::get('/devices/{deviceId}/metrics/cpu/temperatures', [DeviceMetricController::class, 'cpuTemperatures'])
    ->whereUuid('deviceId');

Route::get('/devices/{deviceId}/metrics/cpu/usages', [DeviceMetricController::class, 'cpuUsages'])
    ->whereUuid('deviceId');

Route::get('/devices/{deviceId}/metrics/disk/usages', [DeviceMetricController::class, 'diskUsages'])
    ->whereUuid('deviceId');

Route::get('/devices/{deviceId}/metrics/memory/availables', [DeviceMetricController::class, 'memoryAvailables'])
    ->whereUuid('deviceId');


// Device Commands
Route::get('/devices/{deviceId}/commands', [DeviceCommandController::class, 'index'])
    ->whereUuid('deviceId');


// Device Events
Route::get('/devices/{deviceId}/events', [DeviceEventController::class, 'index'])
    ->whereUuid('deviceId');



//Route::get('/test', [TestController::class, 'index']);
