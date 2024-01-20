<?php

namespace App\Http\Controllers\API\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     public function register(UserRegistrationRequest $request) {
        //Extracting dat from custom request
        $userData = $request->validated();

        //creating user by using static method create
        $userData['password'] = Hash::make($userData['password']);
        $user = User::create($userData);

        //Creating user token for authentication using sanctum
        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
        'user' => $user,
        'token' => $token
        ], 201);


     }
}
