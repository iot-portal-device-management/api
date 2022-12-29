<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

use App\Http\Controllers\Api\DeviceCategoryController;
use App\Http\Controllers\Api\DeviceCommandController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceEventController;
use App\Http\Controllers\Api\DeviceGroupController;
use App\Http\Controllers\Api\DeviceJobController;
use App\Http\Controllers\Api\DeviceMetricController;
use App\Http\Controllers\Api\Mqtt\EndpointController;
use App\Http\Controllers\Api\SavedDeviceCommandController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\UserController;
use App\Models\Device;
use App\Models\DeviceCategory;
use App\Models\DeviceCpuStatistic;
use App\Models\DeviceCpuTemperatureStatistic;
use App\Models\DeviceDiskStatistic;
use App\Models\DeviceGroup;
use App\Models\DeviceJob;
use App\Models\DeviceMemoryStatistic;
use App\Models\SavedDeviceCommand;
use App\Models\User;
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

// VERNEMQ WEBHOOKS ENDPOINT
Route::post('/mqtt/endpoint', [EndpointController::class, 'mqttEndpoint'])
    ->withoutMiddleware('throttle:api')
    ->middleware('throttle:120,1');

// DEVICE REGISTRATION ENDPOINT
Route::post('/devices/register', [DeviceController::class, 'register']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // LOGGED IN USER
    Route::get('/user', [UserController::class, 'user'])
        ->can('viewOwn', User::class);


    // USER STATISTICS
    Route::get('/statistics', [StatisticController::class, 'showStatistics'])
        ->can('viewOverallStatistic', User::class);

    // STATISTICS CPU TEMPERATURES
    Route::get('/statistics/devices/online/cpu/temperatures',
        [StatisticController::class, 'onlineDevicesCpuTemperatures'])
        ->can('view', DeviceCpuTemperatureStatistic::class);

    // STATISTICS CPU USAGES
    Route::get('/statistics/devices/online/cpu/usages', [StatisticController::class, 'onlineDevicesCpuUsages'])
        ->can('view', DeviceCpuStatistic::class);

    // STATISTICS DISK USAGES
    Route::get('/statistics/devices/online/disk/usages', [StatisticController::class, 'onlineDevicesDiskUsages'])
        ->can('view', DeviceDiskStatistic::class);

    // STATISTICS MEMORY AVAILABLES
    Route::get('/statistics/devices/online/memory/availables',
        [StatisticController::class, 'onlineDevicesMemoryAvailables'])
        ->can('view', DeviceMemoryStatistic::class);


    // DEVICES
    Route::get('/devices', [DeviceController::class, 'index'])
        ->can('viewIndex', Device::class);

    Route::post('/devices', [DeviceController::class, 'store'])
        ->can('create', Device::class);

    Route::get('/devices/{device}', [DeviceController::class, 'show'])
        ->whereUuid('device')
        ->can('view', 'device');

    Route::match(['put', 'patch'], '/devices/{device}', [DeviceController::class, 'update'])
        ->whereUuid('device')
        ->can('update', 'device');

    Route::delete('/devices', [DeviceController::class, 'destroySelected'])
        ->can('deleteMany', Device::class);


    // DEVICE GROUPS
    Route::get('/device/groups/options', [DeviceGroupController::class, 'options'])
        ->can('viewIndex', DeviceGroup::class);

    Route::get('/device/groups/{deviceGroup}/devices', [DeviceGroupController::class, 'deviceGroupDevicesIndex'])
        ->whereUuid('deviceGroup')
        ->can('view', 'deviceGroup');

    Route::get('/device/groups', [DeviceGroupController::class, 'index'])
        ->can('viewIndex', DeviceGroup::class);

    Route::post('/device/groups', [DeviceGroupController::class, 'store'])
        ->can('create', DeviceGroup::class);

    Route::get('/device/groups/{deviceGroup}', [DeviceGroupController::class, 'show'])
        ->whereUuid('deviceGroup')
        ->can('view', 'deviceGroup');

    Route::match(['put', 'patch'], '/device/groups/{deviceGroup}', [DeviceGroupController::class, 'update'])
        ->whereUuid('deviceGroup')
        ->can('update', 'deviceGroup');

    Route::delete('/device/groups', [DeviceGroupController::class, 'destroySelected'])
        ->can('deleteMany', DeviceGroup::class);


    // DEVICE CATEGORIES
    Route::get('/device/categories/options', [DeviceCategoryController::class, 'options'])
        ->can('viewIndex', DeviceCategory::class);

    Route::get('/device/categories/{deviceCategory}/devices',
        [DeviceCategoryController::class, 'deviceCategoryDevicesIndex'])
        ->whereUuid('deviceCategory')
        ->can('view', 'deviceCategory');

    Route::get('/device/categories', [DeviceCategoryController::class, 'index'])
        ->can('viewIndex', DeviceCategory::class);

    Route::post('/device/categories', [DeviceCategoryController::class, 'store'])
        ->can('create', DeviceCategory::class);

    Route::get('/device/categories/{deviceCategory}', [DeviceCategoryController::class, 'show'])
        ->whereUuid('deviceCategory')
        ->can('view', 'deviceCategory');

    Route::match(['put', 'patch'], '/device/categories/{deviceCategory}',
        [DeviceCategoryController::class, 'update'])
        ->whereUuid('deviceCategory')
        ->can('update', 'deviceCategory');

    Route::delete('/device/categories', [DeviceCategoryController::class, 'destroySelected'])
        ->can('deleteMany', DeviceCategory::class);


    // DEVICE JOBS
    Route::get('/device/jobs/{deviceJob}/progressStatus', [DeviceJobController::class, 'showProgressStatus'])
        ->whereUuid('deviceJob')
        ->can('view', 'deviceJob');

    Route::get('/device/jobs/{deviceJob}/deviceCommands',
        [DeviceJobController::class, 'deviceJobDeviceCommandsIndex'])
        ->whereUuid('deviceJob')
        ->can('view', 'deviceJob');

    Route::get('/device/jobs', [DeviceJobController::class, 'index'])
        ->can('viewIndex', DeviceJob::class);

    Route::post('/device/jobs', [DeviceJobController::class, 'store'])
        ->can('create', DeviceJob::class);

    Route::get('/device/jobs/{deviceJob}', [DeviceJobController::class, 'show'])
        ->whereUuid('deviceJob')
        ->can('view', 'deviceJob');


    // SAVED DEVICE COMMANDS
    Route::get('/device/commands/saved/options', [SavedDeviceCommandController::class, 'options'])
        ->can('viewIndex', SavedDeviceCommand::class);

    Route::get('/device/commands/saved', [SavedDeviceCommandController::class, 'index'])
        ->can('viewIndex', SavedDeviceCommand::class);

    Route::post('/device/commands/saved', [SavedDeviceCommandController::class, 'store'])
        ->can('create', SavedDeviceCommand::class);

    Route::get('/device/commands/saved/{savedDeviceCommand}', [SavedDeviceCommandController::class, 'show'])
        ->whereUuid('savedDeviceCommand')
        ->can('view', 'savedDeviceCommand');

    Route::delete('/device/commands/saved', [SavedDeviceCommandController::class, 'destroySelected'])
        ->can('deleteMany', SavedDeviceCommand::class);


    // DEVICE METRICS AKA. DEVICE METRICS CHARTS
    Route::get('/devices/{device}/metrics/cpu/temperatures', [DeviceMetricController::class, 'cpuTemperatures'])
        ->whereUuid('device')
        ->can('view', 'device');

    Route::get('/devices/{device}/metrics/cpu/usages', [DeviceMetricController::class, 'cpuUsages'])
        ->whereUuid('device')
        ->can('view', 'device');

    Route::get('/devices/{device}/metrics/disk/usages', [DeviceMetricController::class, 'diskUsages'])
        ->whereUuid('device')
        ->can('view', 'device');

    Route::get('/devices/{device}/metrics/memory/availables', [DeviceMetricController::class, 'memoryAvailables'])
        ->whereUuid('device')
        ->can('view', 'device');


    // DEVICE OTA COMMAND TRIGGER ENDPOINT
    Route::post('/devices/{device}/triggerDeviceCommand', [DeviceCommandController::class, 'triggerDeviceCommand'])
        ->whereUuid('device')
        ->can('triggerDeviceCommand', 'device');


    // DEVICE COMMANDS
    Route::get('/devices/{device}/deviceCommands', [DeviceCommandController::class, 'index'])
        ->whereUuid('device')
        ->can('view', 'device');


    // DEVICE EVENTS
    Route::get('/devices/{device}/deviceEvents', [DeviceEventController::class, 'index'])
        ->whereUuid('device')
        ->can('view', 'device');
});
