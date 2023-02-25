<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed|max:255',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirmation_token' => Str::random(40),
        ]);
    
        return response()->json([
            'message' => 'User registered successfully',
            'token' => $user->confirmation_token,
        ], 201);
    }

    public function confirmEmail($token)
    {
        $user = User::where('confirmation_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid confirmation token'], 400);
        }

        $user->is_confirmed = true;
        $user->confirmation_token = null;
        $user->save();

        return response()->json(['message' => 'Email confirmed successfully'], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials'
            ], 401);
        }
    }
}
