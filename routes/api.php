<?php

use App\Http\Controllers\Api\CommandHistoryController;
use App\Http\Controllers\Api\DeviceCategoryController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\EventHistoryController;
use App\Http\Controllers\Api\MetricController;
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


// Device Categories
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
