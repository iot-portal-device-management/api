<?php

use App\Http\Controllers\Api\DeviceCategoryController;
use App\Http\Controllers\Api\DeviceController;
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
Route::delete('/devices', [DeviceController::class, 'destroySelected']);

Route::apiResource('/devices', DeviceController::class);


// Device Categories
Route::get('/device/categories/options', [DeviceCategoryController::class, 'options']);




