<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;

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

Route::prefix("/v1")->middleware('auth:sanctum')->controller(JobController::class)->group(function () {
    Route::get("jobs", "index")->name('jobs.index');
    Route::post("/jobs", "store")->name('jobs.store');
});
