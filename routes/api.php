<?php

use App\Http\Controllers\Api\TweetController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Resources\TweetResource;
use App\Http\Resources\UserResource;
use App\Models\Tweet;
use App\Models\User;
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

    Route::get('/{id}/followings/{followedUser}/tweets', function($id, $followedUser) {
        $fUser = User::whereHas('followers', function ($query) use ($id, $followedUser) {
            $query->where('follower', $id)->where('followed_user', $followedUser);
        })->first();

        $tweets = TweetResource::collection($fUser->tweets);

        return response()->json($tweets);
    })->name('followedUserTweets');

    // suggestions are the follows of a followed user
    Route::get('/{id}/suggestions/follows', function($id) {
        $user = User::findOrFail($id);

        $follows = $user->followings->map(function($p) use ($user) {
            return $p->followed->followings->map(function ($sugg) use($user) {
                $ffUser = $sugg->followed;

                if(!$ffUser->isFollowedBy($user) && $ffUser->id != $user->id)
                    return new UserResource($sugg->followed);
            });
        });

        $suggestions = array();

        foreach($follows as $f1) {
            foreach($f1 as $f2) {
                if($f2 && !in_array($f2, $suggestions)) {
                    $suggestions[] = $f2;
                }
            }
        }

        return response()->json($suggestions);
    })->name('suggestions');
});

// tweets
Route::prefix('tweets')->name('tweets.')->group(function() {
    Route::get('/', [TweetController::class, 'index'])->name('index');
    Route::post('/create', [TweetController::class, 'store'])->name('store');
    Route::put('/update/{tweet}', [TweetController::class, 'update'])->name('update');
    Route::delete('/{tweet}', [TweetController::class, 'destroy'])->name('destroy');

});

// follows
Route::prefix('followings')->name('followings.')->group(function() {
    Route::get('/{follower}/{followedUser}', [FollowController::class, 'store'])->name('store');
    Route::delete('/{user}/{followedUser}', [FollowController::class, 'destroy'])->name('destroy');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});