<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Admin API Routes
Route::prefix('admin')->group(function () {
    // Get all sub-competitions
    Route::get('/competitions/all/sub-competitions', [App\Http\Controllers\Admin\CompetitionController::class, 'getAllSubCompetitions']);
    
    // Get sub-competitions by competition ID
    Route::get('/competitions/{competition}/sub-competitions', [App\Http\Controllers\Admin\CompetitionController::class, 'getSubCompetitions']);
});
