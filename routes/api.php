<?php

use App\Http\Controllers\Api\TweetController;
use App\Http\Controllers\Api\UserController;
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

// Route::apiResource('users', UserController::class);

// users
Route::prefix('users')->name('users.')->group(function() {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store'); // register
    Route::post('/login', [UserController::class, 'login'])->name('login'); // login
});

// tweets
Route::prefix('tweets')->name('tweets.')->group(function() {
    Route::get('/', [TweetController::class, 'index'])->name('index');
    Route::post('/create', [TweetController::class, 'store'])->name('store');
    Route::put('/update/{tweet}', [TweetController::class, 'update'])->name('update');
    Route::delete('/{tweet}', [TweetController::class, 'destroy'])->name('destroy');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});