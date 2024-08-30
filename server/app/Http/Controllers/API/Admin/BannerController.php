<?php

namespace App\Http\Controllers\API\Admin;

use Exception;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'apiVersion' => '1.0',
                'data' => Banner::all()
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
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\Banner\StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            if (!request()->user()->tokenCan('banner:store')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = Str::uuid() . '.' . $request->image->extension();
                Storage::disk('public')->putFileAs("media/banner/", $request->image, $imageName);
            }

            $banner = new Banner();
            $banner->title = $request->title;
            $banner->description = $request->description;
            $banner->image = $imageName;
            $banner->status = $request->status;
            $banner->save();

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $banner
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
     * Display the specified resource.
     * @param \App\Models\Banner $banner
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Banner $banner)
    {
        try {
            if (!request()->user()->tokenCan('banner:show')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $banner
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
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Banner\UpdateRequest $request
     * @param \App\Models\Banner $banner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('banner:update')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $banner = Banner::findOrFail($id);

            if ($request->hasFile('image')) {
                $imageName = Str::uuid() . '.' . $request->image->extension();
                Storage::disk('public')->putFileAs("media/banner/", $request->image, $imageName);

                if (Storage::disk('public')->exists("media/banner/{$banner->image}")) {
                    Storage::disk('public')->delete("media/banner/{$banner->image}");
                }
            }

            $banner->title = $request->title;
            $banner->image = $imageName ?? $banner->image;
            $banner->description = $request->description;
            $banner->status = $request->status;
            $banner->save();

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $banner
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
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('banner:update')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $banner = Banner::findOrFail($id);

            if (Storage::disk('public')->exists("media/banner/{$banner->image}")) {
                Storage::disk('public')->delete("media/banner/{$banner->image}");
            }

            return response()->json([
                'apiVersion' => '1.0',
                'data' => [
                    'deleted' => $banner->delete() ? true : false
                ]
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
