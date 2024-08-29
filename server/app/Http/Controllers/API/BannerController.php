<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = Banner::all();

        return response()->json([
            'apiVersion' => '1.0',
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\Banner\StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
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
            $banner->created_at = now();
            $banner->save();

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $banner
            ], Response::HTTP_OK);
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
        return response()->json([
            'apiVersion' => '1.0',
            'data' => $banner
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Banner\UpdateRequest $request
     * @param \App\Models\Banner $banner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Banner $banner): JsonResponse
    {
        try {
            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = Str::uuid() . '.' . $request->image->extension();
                Storage::disk('public')->putFileAs("media/banner/", $request->image, $imageName);
            }

            if (Storage::disk('public')->exists("media/banner/{$banner->image}")) {
                Storage::disk('public')->delete("media/banner/{$banner->image}");
            }

            $banner->title = $request->title;
            $banner->image = $imageName;
            $banner->description = $request->description;
            $banner->status = $request->status;
            $banner->updated_at = now();
            $banner->save();

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $banner
            ], Response::HTTP_OK);
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
     * @param \App\Models\Banner $banner
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
