<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            //Delete old  user tokens if exists
            $user->tokens()->delete();
            //Generate user token using sunctum
            $token = $user->createToken('user-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        }

        return response()->json(['error' => 'Invalid Credentials'], 401);
    }

    public function logout($userId) {
        $user = User::findOrFail($userId);
        //Delete user tokens
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
