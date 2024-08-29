<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle login request
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only(['username', 'password']))) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Request is missing valid authentication credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // untuk role user batasi akses kesini
        $can = [
            'user:profile',
            'user:profile_update'
        ];

        // untuk role admin, loskan saja
        if (Auth::user()->roles == 'administrator') {
            $can = ['*'];
        }

        $token = $request->user()->createToken($request->username, $can);
        return response()->json([
            'apiVersion' => '1.0',
            'data' => [
                'user' => Auth::user(),
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_in' => 3600
            ]
        ], Response::HTTP_OK);
    }
}
