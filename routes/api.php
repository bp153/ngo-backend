<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ngo\ngoController;
use App\Http\Controllers\ngo\yearlyDashboardController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\national\nationalController;

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

Route::post('login', [loginController::class, 'login']);
Route::get('ngo/get-dashboard', [ngoController::class, 'totalSubmissions']);

Route::get('ngo/get-five-entries', [ngoController::class, 'lastFiveEntries']);
Route::post('ngo/make-entry', [ngoController::class, 'makeEntry']);
Route::get('ngo/select-all', [ngoController::class, 'selectAll']);
Route::get('ngo/view-entry', [ngoController::class, 'getDataById']);
Route::get('ngo/export-data', [ngoController::class, 'export']);
Route::get('ngo/iptp-data', [ngoController::class, 'Iptp']);
Route::get('ngo/sensitization', [ngoController::class, 'sensitization']);
Route::get('ngo/media-engagement', [ngoController::class, 'mediaEngagement']);
Route::get('ngo/people-reached', [ngoController::class, 'peopelReached']);
Route::get('ngo/get-name', [ngoController::class, 'ngoName']);
Route::get('ngo/search-by-year', [ngoController::class, 'selectByYear']);
Route::get('ngo/export-data-year', [ngoController::class, 'exportByYear']);
Route::get('ngo/get-full-name', [ngoController::class, 'getUserName']);
Route::get('ngo/pregnant-women', [ngoController::class, 'followUp']);
Route::post('ngo/update-entry', [ngoController::class, 'updateEntry']);
Route::get('ngo/get-dashboard-year', [yearlyDashboardController::class, 'totalSubmissions']);
Route::get('ngo/iptp-data-year', [yearlyDashboardController::class, 'Iptp']);
Route::get('ngo/sensitization-year', [yearlyDashboardController::class, 'sensitization']);
Route::get('ngo/media-engagement-year', [yearlyDashboardController::class, 'mediaEngagement']);
Route::get('ngo/people-reached-year', [yearlyDashboardController::class, 'peopelReached']);
Route::get('ngo/pregnant-women-year', [yearlyDashboardController::class, 'followUp']);
Route::get('ngo/get-year', [ngoController::class, 'getYear']);
Route::get('ngo/get-username', [ngoController::class, 'getUserCredentials']);
Route::post('/update-account', [ngoController::class, 'updateAccount']);



Route::get('supervisor/get-dashboard', [nationalController::class, 'totalSubmissions']);
Route::get('supervisor/iptp-data', [nationalController::class, 'Iptp']);
Route::get('supervisor/sensitization', [nationalController::class, 'sensitization']);
Route::get('supervisor/media-engagement', [nationalController::class, 'mediaEngagement']);
Route::get('supervisor/people-reached', [nationalController::class, 'peopelReached']);
Route::post('supervisor/add-user', [nationalController::class, 'addUser']);
Route::get('supervisor/get-ngos', [nationalController::class, 'getNgos']);
Route::get('supervisor/pregnant-women', [nationalController::class, 'followUp']);
Route::get('supervisor/search-by-year', [nationalController::class, 'selectByYear']);
Route::get('supervisor/select-all', [nationalController::class, 'selectAll']);
Route::get('supervisor/export-data', [nationalController::class, 'export']);
Route::get('supervisor/export-data-year', [nationalController::class, 'exportTotalByYear']);
