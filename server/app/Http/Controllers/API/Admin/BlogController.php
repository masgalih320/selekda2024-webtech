<?php

namespace App\Http\Controllers\API\Admin;

use Exception;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreRequest;
use App\Http\Requests\Blog\UpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
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
                'data' => Blog::all()
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
     * @param \App\Http\Requests\Blog\StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('blog:store')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $imageName = null;
            if ($request->hasFile('featured_image')) {
                $imageName = Str::uuid() . '.' . $request->featured_image->extension();
                Storage::disk('public')->putFileAs("media/blog/", $request->featured_image, $imageName);
            }

            $blog = new Blog();
            $blog->created_by_id = Auth::id();
            $blog->featured_image = $imageName;
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->tags = explode(',', $request->tags);
            $blog->save();

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $blog
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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('blog:show')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $blog = Blog::findOrFail($id);

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $blog
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
     * @param \App\Http\Requests\Blog\UpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('blog:update')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $blog = Blog::findOrFail($id);

            if ($request->hasFile('featured_image')) {
                $imageName = Str::uuid() . '.' . $request->featured_image->extension();
                Storage::disk('public')->putFileAs("media/blog/", $request->featured_image, $imageName);

                if (Storage::disk('public')->exists("media/blog/{$blog->featured_image}")) {
                    Storage::disk('public')->delete("media/blog/{$blog->featured_image}");
                }
            }

            $blog->featured_image = $imageName ?? $blog->featured_image;
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->tags = explode(',', $request->tags) ?? [];
            $blog->save();

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $blog
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
            if (!request()->user()->tokenCan('blog:delete')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $blog = Blog::findOrFail($id);

            if (Storage::disk('public')->exists("media/blog/{$blog->featured_image}")) {
                Storage::disk('public')->delete("media/blog/{$blog->featured_image}");
            }

            return response()->json([
                'apiVersion' => '1.0',
                'data' => [
                    'deleted' => $blog->delete() ? true : false
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
