<?php

use App\Http\Controllers\Api\DeviceCommandController;
use App\Http\Controllers\Api\DeviceCategoryController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceGroupController;
use App\Http\Controllers\Api\DeviceEventController;
use App\Http\Controllers\Api\DeviceMetricController;
use App\Http\Controllers\Api\TestController;
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

Route::get('/devices/{deviceId}', [DeviceController::class, 'show']);

Route::match(['put', 'patch'], '/devices/{deviceId}', [DeviceController::class, 'update']);

Route::delete('/devices', [DeviceController::class, 'destroySelected']);


// Device Groups
Route::get('/device/groups', [DeviceGroupController::class, 'index']);

Route::post('/device/groups', [DeviceGroupController::class, 'store']);

Route::get('/device/groups/{deviceGroupId}', [DeviceGroupController::class, 'show']);

Route::match(['put', 'patch'], '/device/groups/{deviceGroupId}', [DeviceGroupController::class, 'update']);

Route::delete('/device/groups', [DeviceGroupController::class, 'destroySelected']);

Route::get('/device/groups/{deviceGroupId}/devices', [DeviceGroupController::class, 'deviceGroupDevicesIndex']);


// Device Categories
Route::get('/device/categories', [DeviceCategoryController::class, 'index']);

Route::post('/device/categories', [DeviceCategoryController::class, 'store']);

Route::get('/device/categories/{deviceCategoryId}', [DeviceCategoryController::class, 'show']);

Route::match(['put', 'patch'], '/device/categories/{deviceCategoryId}', [DeviceCategoryController::class, 'update']);

Route::delete('/device/categories', [DeviceCategoryController::class, 'destroySelected']);

Route::get('/device/categories/{deviceCategoryId}/devices', [DeviceCategoryController::class, 'deviceCategoryDevicesIndex']);

Route::get('/device/categories/options', [DeviceCategoryController::class, 'options']);


// Device OTA commands trigger endpoint
Route::post('/devices/{id}/commands', [DeviceController::class, 'commands']);


// Device DeviceMetric aka. Device Charts
Route::get('/devices/{deviceId}/metrics/cpu/temperatures', [DeviceMetricController::class, 'cpuTemperatures']);

Route::get('/devices/{deviceId}/metrics/cpu/usages', [DeviceMetricController::class, 'cpuUsages']);

Route::get('/devices/{deviceId}/metrics/disk/usages', [DeviceMetricController::class, 'diskUsages']);

Route::get('/devices/{deviceId}/metrics/memory/availables', [DeviceMetricController::class, 'memoryAvailables']);


// Device commands and Device events
Route::get('/devices/{deviceId}/commands', [DeviceCommandController::class, 'index']);

Route::get('/devices/{deviceId}/events', [DeviceEventController::class, 'index']);



//Route::get('/test', [TestController::class, 'index']);
