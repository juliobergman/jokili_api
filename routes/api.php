<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
Route::post('/login', [AuthController::class, 'login']);

// User
Route::middleware('auth:sanctum')->prefix('/user')->group(function(){
    Route::get('/auth', [AuthController::class, 'auth']);
    Route::get('/show/{user}', [UserController::class, 'show']);
    // -----------------------------------------------------------
    Route::get('/', [UserController::class, 'index']);
    Route::put('/update/{user}', [UserController::class, 'update']);
    // -----------------------------------------------------------
    Route::post('/store', [UserController::class, 'store']);
    Route::get('/create', [UserController::class, 'create']);
    Route::get('/edit', [UserController::class, 'edit']);
    Route::get('/destroy/{user}', [UserController::class, 'destroy']);
});