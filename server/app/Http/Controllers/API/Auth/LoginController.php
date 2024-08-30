<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle login request
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::where('username', $request->username)->first();

            if (empty($user) || !Hash::check($request->password, $user->password)) {
                throw new Exception("Request is missing valid authentication credentials");
            }

            // untuk role user batasi akses kesini
            $can = [
                'user:profile',
                'user:profile_update'
            ];

            // untuk role admin, loskan saja
            if ($user->roles == 'administrator') {
                $can = ['*'];
            }

            $token = $user->createToken($request->username, $can);
            return response()->json([
                'apiVersion' => '1.0',
                'data' => [
                    'user' => $user,
                    'token' => $token->plainTextToken,
                    'token_type' => 'Bearer',
                    'expires_in' => 3600
                ]
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Handle user logout
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $tokenId = Str::before(request()->bearerToken(), '|');
            $user = User::where('id', auth('sanctum')->user()->id)->first();
            $user->tokens()->where('id', $tokenId)->delete();

            return response()->json([
                'apiVersion' => '1.0',
                'success' => true,
                'code' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
