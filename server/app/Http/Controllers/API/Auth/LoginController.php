<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only(['username', 'password']))) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => 401,
                'message' => 'Request is missing valid authentication credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $request->user()->createToken($request->username);
        return response()->json([
            'apiVersion' => '1.0',
            'data' => [
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_in' => 3600
            ]
        ], Response::HTTP_OK);
    }
}
