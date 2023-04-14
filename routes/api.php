<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SupporterController;

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

Route::get("/heartbeat", function () {
    return response()->json([
        "code" => 200,
        "status" => "ok"
    ]);
});

Route::prefix("supporters")->group( function() {
    Route::get("{site}", [SupporterController::class, 'ApiGet']);
    Route::post("{site}", [SupporterController::class, 'ApiPost']);
});
