<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully!'], 201);
    }


    public function login(Request $request){
        try {
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $accessToken = $user->createToken('API Token')->plainTextToken;
                $refreshToken = $user->createToken('API Refresh Token')->plainTextToken;
    
                return response()->json([
                    'success' => true,
                    'message' => 'User logged successfully!',
                    'data' => [
                        'access_token' => $accessToken,
                        'refresh_token' => $refreshToken, // Tambahkan refresh token ke respons
                        'token_type' => 'Bearer',
                        'name' => $user->name,
                        'email' => $user->email,
                        'profile_picture' => $user->profile_picture
                    ],
                    
                ]);
            } else {
                return response()->json(['message' => 'Invalid Email or Password'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $th->getMessage()
            ], 500);
        }
    }
    


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $refreshToken = $request->refresh_token;

        // Verifikasi apakah refresh token valid
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($refreshToken);

        if (!$token) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        // Dapatkan pengguna dari token
        $user = $token->tokenable;

        // Buat access token baru
        $accessToken = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully!',
            'data' => [
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ],
        ]);
    }
}
