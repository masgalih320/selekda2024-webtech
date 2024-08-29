<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle login request
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RegisterRequest $request)
    {
        try {
            $imageName = null;
            if ($request->hasFile('profile_picture')) {
                $imageName = Str::uuid() . '.' . $request->profile_picture->extension();
                Storage::disk('public')->putFileAs("media/profile/", $request->profile_picture, $imageName);
            }

            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->date_of_birth = $request->date_of_birth;
            $user->password = Hash::make($request->password);
            $user->profile_picture = $request->profile_picture;
            $user->save();

            // untuk role user batasi akses kesini
            $can = [
                'user:profile',
                'user:profile_update'
            ];

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
}
