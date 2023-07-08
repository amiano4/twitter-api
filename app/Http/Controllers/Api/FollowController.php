<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Following;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($follower, $followedUser)
    {
        if(Following::create([
            'follower' => $follower,
            'followed_user' => $followedUser,
        ])) {
            return 1;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Following $following)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Following $following)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user, $followedUser)
    {
        $following = Following::where('follower', $user)->where('followed_user', $followedUser)->first();
        return $following->delete();
    }
}
