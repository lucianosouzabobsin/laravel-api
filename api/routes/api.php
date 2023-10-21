<?php

use App\Http\Controllers\ModuleController;
use App\Http\Controllers\UserController;
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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user', [UserController::class, 'details']);
    Route::get('/module-list', [ModuleController::class, 'list']);
    Route::post('/module-create', [ModuleController::class, 'create']);
    Route::post('/module-update', [ModuleController::class, 'update']);
    Route::post('/module-active', [ModuleController::class, 'active']);
});
