<?php

use App\Http\Controllers\Api\DropdownController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SurveyController;
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

// Login
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

// Dropdown
Route::get('districts', [DropdownController::class, 'getDistricts']);
Route::get('ulb-name', [DropdownController::class, 'getUlbName']);
Route::get('distances', [DropdownController::class, 'getDistances']);
Route::get('extents', [DropdownController::class, 'getExtents']);

Route::middleware('jwtauth')->group(function () {
    // Surveys
    Route::post('survey', [SurveyController::class, 'addSurvey']);
    Route::get('survey', [SurveyController::class, 'getSurvey']);

    // Survey OTP
    Route::post('survey/otp', [SurveyController::class, 'createOTP']);
    Route::post('survey/verify-otp', [SurveyController::class, 'verifyOTP']);
});
