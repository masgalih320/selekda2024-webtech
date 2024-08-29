<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Profile\UpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;

class ProfileController extends Controller
{
    /**
     * Show user profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('user:profile')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            return response()->json([
                'apiVersion' => '1.0',
                'data' => Auth::user()
            ], Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_FORBIDDEN,
                'message' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update user profile
     * @param \App\Http\Requests\Profile\UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('user:profile_update')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $imageName = null;
            if ($request->hasFile('profile_picture')) {
                $imageName = Str::uuid() . '.' . $request->profile_picture->extension();
                Storage::disk('public')->putFileAs("media/profile/", $request->profile_picture, $imageName);
            }

            $user = User::where('id', Auth::id())->first();
            if (Storage::disk('public')->exists("media/profile/{$user->profile_picture}")) {
                Storage::disk('public')->delete("media/profile/{$user->profile_picture}");
            }

            (new User)
                ->where('id', Auth::id())
                ->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'date_of_birth' => $request->date_of_birth,
                    'profile_picture' => $imageName
                ]);

            return response()->json([
                'apiVersion' => '1.0',
                'data' => User::where('id', Auth::id())->first()
            ], Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_FORBIDDEN,
                'message' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
