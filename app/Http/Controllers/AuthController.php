<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     *
     * User Login
     *
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        Auth::user()->tokens()->delete();

        return response()->json([
            'access_token' => Auth::user()->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}
