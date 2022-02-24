<?php

use Illuminate\Http\Request;
use GrahamCampbell\ResultType\Result;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NomineeController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ElectionResultController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

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
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/new_register', [RegisteredUserController::class, 'store']);
// Password Reset
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->middleware('guest')->name('password.update');
// Verify Email
Route::post('/email/verification-notification', [AuthController::class, 'emailVerificationSend'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'emailVerificationVerify'])->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// User
Route::middleware('auth:sanctum')->prefix('/user')->group(function(){
    Route::get('/auth', [AuthController::class, 'auth']);
    Route::get('/show/{user}', [UserController::class, 'show']);
    Route::put('/update/{user}', [UserController::class, 'update']);
    Route::get('/godfathers', [UserController::class, 'godfathers']);
    Route::get('/role/{role?}', [UserController::class, 'role']);
    Route::get('/group/{group?}', [UserController::class, 'group']);
    Route::get('/statistics', [UserController::class, 'statistics']);
    // -----------------------------------------------------------
});

// Elections
Route::middleware('auth:sanctum')->prefix('/election')->group(function(){
    Route::get('/', [ElectionController::class, 'index']);
    Route::get('/{election}', [ElectionController::class, 'show']);
    Route::get('/{election}/position', [PositionController::class, 'index']);
    Route::get('/position/{position}', [PositionController::class, 'show']);
    Route::get('/{position}/nominee', [NomineeController::class, 'index']);
    Route::post('/submit', [ElectionResultController::class, 'submit']);
    Route::post('/results', [ElectionResultController::class, 'results']);
});


// Uploads
Route::middleware('auth:sanctum')->prefix('/upload')->group(function(){
    Route::post('/avatar/user', [UploadController::class, 'useravatar']);
});

// Resources
Route::get('/country', [CountryController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/rank', [RankController::class, 'index'])->middleware(['auth:sanctum']);




Route::get('/clear-cache', function() {
    $configCache = Artisan::call('config:cache');
    $clearCache = Artisan::call('cache:clear');
    return [$configCache, $clearCache];
});