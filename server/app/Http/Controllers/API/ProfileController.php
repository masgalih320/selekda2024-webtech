<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show user profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        return response()->json([
            'apiVersion' => '1.0',
            'data' => Auth::user()
        ], Response::HTTP_OK);
    }

    /**
     * Update user profile
     * @param \App\Http\Requests\Profile\UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        try {
            (new User)
                ->where('id', Auth::id())
                ->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'date_of_birth' => $request->date_of_birth,
                    'profile_picture' => $request->profile_picture
                ]);

            return response()->json([
                'apiVersion' => '1.0',
                'data' => User::where('id', Auth::id())->first()
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
