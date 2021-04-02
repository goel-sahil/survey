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

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::get('districts', [DropdownController::class, 'getDistricts']);
Route::get('ulb-name', [DropdownController::class, 'getUlbName']);

Route::middleware('jwtauth')->group(function () {
    Route::post('survey', [SurveyController::class, 'addSurvey']);
});
