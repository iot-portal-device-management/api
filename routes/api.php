<?php

use App\Http\Controllers\Api\CommandHistoryController;
use App\Http\Controllers\Api\DeviceCategoryController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceGroupController;
use App\Http\Controllers\Api\EventHistoryController;
use App\Http\Controllers\Api\MetricController;
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


// Devices
Route::get('/devices', [DeviceController::class, 'index']);

Route::post('/devices', [DeviceController::class, 'store']);

Route::get('/devices/{id}', [DeviceController::class, 'show']);

Route::match(['put', 'patch'], '/devices/{id}', [DeviceController::class, 'update']);

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


// Device Metrics aka. Device Charts
Route::get('/devices/{deviceId}/metrics/cpu/temperatures', [MetricController::class, 'cpuTemperatures']);

Route::get('/devices/{deviceId}/metrics/cpu/usages', [MetricController::class, 'cpuUsages']);

Route::get('/devices/{deviceId}/metrics/disk/usages', [MetricController::class, 'diskUsages']);

Route::get('/devices/{deviceId}/metrics/memory/availables', [MetricController::class, 'memoryAvailables']);


// Device Command Histories and Event Histories
Route::get('/devices/{deviceId}/commands/histories', [CommandHistoryController::class, 'index']);

Route::get('/devices/{deviceId}/events/histories', [EventHistoryController::class, 'index']);



//Route::get('/test', [TestController::class, 'index']);
