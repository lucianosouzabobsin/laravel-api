<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\ModuleActionController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\UserGroupHasAbilitiesController;
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
| Atenção!!! Sempre que for testar no POSTMAN, não esquecer de colocar no HEADER:
| Content-Type: application/json
| Accept: application/json
|
*/

Route::post('/login', [UserController::class, 'login']);

Route::post('/register', [UserController::class, 'register'])
    ->middleware(['auth:sanctum', 'ability:user:register']);

Route::get('/user', [UserController::class, 'details'])
    ->middleware(['auth:sanctum', 'ability:user:details']);

Route::get('/is-logged-in', [UserController::class, 'isLoggedIn']);

#Module
Route::get('/module-list', [ModuleController::class, 'list'])
    ->middleware(['auth:sanctum', 'ability:module:list']);
Route::post('/module-create', [ModuleController::class, 'create'])
    ->middleware(['auth:sanctum', 'ability:module:create']);
Route::post('/module-update', [ModuleController::class, 'update'])
    ->middleware(['auth:sanctum', 'ability:module:update']);
Route::post('/module-active', [ModuleController::class, 'active'])
    ->middleware(['auth:sanctum', 'ability:module:active']);

#ModuleAction
Route::get('/module-action-list', [ModuleActionController::class, 'list'])
    ->middleware(['auth:sanctum', 'ability:moduleaction:list']);
Route::post('/module-action-create', [ModuleActionController::class, 'create'])
    ->middleware(['auth:sanctum', 'ability:moduleaction:create']);
Route::post('/module-action-update', [ModuleActionController::class, 'update'])
    ->middleware(['auth:sanctum', 'ability:moduleaction:update']);
Route::post('/module-action-active', [ModuleActionController::class, 'active'])
    ->middleware(['auth:sanctum', 'ability:moduleaction:active']);

#UserGroup
Route::post('/user-group-list', [UserGroupController::class, 'list'])
    ->middleware(['auth:sanctum', 'ability:usergroup:list']);
Route::post('/user-group-create', [UserGroupController::class, 'create'])
    ->middleware(['auth:sanctum', 'ability:usergroup:create']);
Route::post('/user-group-update', [UserGroupController::class, 'update'])
    ->middleware(['auth:sanctum', 'ability:usergroup:update']);
Route::post('/user-group-active', [UserGroupController::class, 'active'])
    ->middleware(['auth:sanctum', 'ability:usergroup:active']);

#Ability
Route::get('/ability-list', [AbilityController::class, 'list'])
    ->middleware(['auth:sanctum', 'ability:ability:list']);
Route::post('/ability-create', [AbilityController::class, 'create'])
    ->middleware(['auth:sanctum', 'ability:ability:create']);
Route::post('/ability-active', [AbilityController::class, 'active'])
    ->middleware(['auth:sanctum', 'ability:ability:active']);
Route::get('/ability-run', [AbilityController::class, 'run'])
    ->middleware(['auth:sanctum', 'ability:*']);

#UserGroupAbility
Route::post('/user-group-ability-list', [UserGroupHasAbilitiesController::class, 'list'])
    ->middleware(['auth:sanctum', 'ability:usergroupability:list']);
Route::post('/user-group-ability-create', [UserGroupHasAbilitiesController::class, 'create'])
    ->middleware(['auth:sanctum', 'ability:usergroupability:create']);


