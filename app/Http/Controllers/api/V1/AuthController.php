<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'data' => $user,
            'message' => 'Successful registration, log in',
        ]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        if (!Auth::attempt($data)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ]);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response()->json([
            'message' => 'Successful authorization',
            'access_token' => $accessToken
        ]);
    }
}
