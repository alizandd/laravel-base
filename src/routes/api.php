<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->namespace('Api\V1')->group(function () {
        Route::post('/generate-otp', [AuthController::class,'generateOtp'])->middleware('throttle:500,1440'); // 5 attempts per 1440 minutes (24 hours);
        Route::post('/register', [AuthController::class,'register'])->middleware('throttle:500,1440'); // 5 attempts per 1440 minutes (24 hours);

        Route::middleware(['auth:api'])->group(function () {
            Route::get('/user/profile', [UserController::class,'index']);
        });
});
