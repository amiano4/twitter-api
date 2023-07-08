<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users|min:6',
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password'])
        ]);

        if($user) {
            return response('You have successfully created an account!');
        } else {
            return response('Unable to process your registration data.', 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Login user
     */
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required|string|min:6',
            'password' => 'required'
        ]);


        if($user = User::where('username', $credentials['username'])->first()) {
            if(Hash::check($credentials['password'], $user['password'])) {
                return response()->json([
                    'message' => 'Sucessfully login!',
                    'user' => new UserResource($user)
                ]);
            } else {
                return response('Incorrect password entered.', 400);
            }
        } else {
            return response('Invalid username', 400);
        }
    }

    public function logout(User $user) {
        $user->remember_token = null;
        $user->save();

        return response("Logged out successfully!");
    }
}
