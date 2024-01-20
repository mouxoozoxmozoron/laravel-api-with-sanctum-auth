<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Posts\PostController;
use App\Http\Controllers\API\Users\UserController;
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



//Authentication
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout/{userId}', [AuthController::class, 'logout']);
});

Route::post('register', [UserController::class, 'register']);

//Athenticated routes
Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('posts', PostController::class);
});
