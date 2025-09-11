<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Resources\Api\ProductResource;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/auth/facebook/redirect', [AuthController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('threads', \App\Http\Controllers\Api\ThreadsController::class);
    Route::post('/threads/{thread}/comments', [\App\Http\Controllers\Api\CommentsController::class, 'store']);

    Route::apiResource('/products', ProductController::class);

    Route::apiResource('tutorials', \App\Http\Controllers\Api\TutorialController::class);
    Route::apiResource('reviews', \App\Http\Controllers\Api\ReviewController::class);
});
