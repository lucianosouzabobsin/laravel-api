<?php

use App\Http\Controllers\ModuleActionController;
use App\Http\Controllers\ModuleActionPermissionController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
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

Route::post('/login', [UserController::class, 'login']);

#TODO - adicionar habilidade para este endpoint
Route::post('/register', [UserController::class, 'register']);

Route::get('/user', [UserController::class, 'details'])
    ->middleware(['auth:sanctum', 'abilities:user:details']);

#Module
Route::get('/module-list', [ModuleController::class, 'list'])
    ->middleware(['auth:sanctum', 'abilities:module:list']);
Route::post('/module-create', [ModuleController::class, 'create'])
    ->middleware(['auth:sanctum', 'abilities:module:create']);
Route::post('/module-update', [ModuleController::class, 'update'])
    ->middleware(['auth:sanctum', 'abilities:module:update']);
Route::post('/module-active', [ModuleController::class, 'active'])
    ->middleware(['auth:sanctum', 'abilities:module:active']);

#ModuleAction
Route::get('/module-action-list', [ModuleActionController::class, 'list'])
    ->middleware(['auth:sanctum', 'abilities:moduleaction:list']);
Route::post('/module-action-create', [ModuleActionController::class, 'create'])
    ->middleware(['auth:sanctum', 'abilities:moduleaction:create']);
Route::post('/module-action-update', [ModuleActionController::class, 'update'])
    ->middleware(['auth:sanctum', 'abilities:moduleaction:update']);
Route::post('/module-action-active', [ModuleActionController::class, 'active'])
    ->middleware(['auth:sanctum', 'abilities:moduleaction:active']);

#UserGroup
Route::get('/user-group-list', [UserGroupController::class, 'list'])
    ->middleware(['auth:sanctum', 'abilities:usergroup:list']);
Route::post('/user-group-create', [UserGroupController::class, 'create'])
    ->middleware(['auth:sanctum', 'abilities:usergroup:create']);
Route::post('/user-group-update', [UserGroupController::class, 'update'])
    ->middleware(['auth:sanctum', 'abilities:usergroup:update']);
Route::post('/user-group-active', [UserGroupController::class, 'active'])
    ->middleware(['auth:sanctum', 'abilities:usergroup:active']);

#ModuleActionPermission
Route::get('/module-action-permission-list', [ModuleActionPermissionController::class, 'list'])
    ->middleware(['auth:sanctum', 'abilities:moduleactionpermission:list']);
Route::post('/module-action-permission-create', [ModuleActionPermissionController::class, 'create'])
    ->middleware(['auth:sanctum', 'abilities:moduleactionpermission:create']);
Route::post('/module-action-permission-update', [ModuleActionPermissionController::class, 'update'])
    ->middleware(['auth:sanctum', 'abilities:moduleactionpermission:update']);
Route::post('/module-action-permission-active', [ModuleActionPermissionController::class, 'active'])
    ->middleware(['auth:sanctum', 'abilities:moduleactionpermission:active']);

