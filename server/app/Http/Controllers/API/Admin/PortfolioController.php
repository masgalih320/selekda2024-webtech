<?php

namespace App\Http\Controllers\API\Admin;

use Exception;
use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portfolio\StoreRequest;
use App\Http\Requests\Portfolio\UpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('portfolio:list')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            return response()->json([
                'apiVersion' => '1.0',
                'data' => Portfolio::all()
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
     * @param \App\Http\Requests\Portfolio\StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('portfolio:store')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = Str::uuid() . '.' . $request->image->extension();
                Storage::disk('public')->putFileAs("media/portfolio/", $request->image, $imageName);
            }

            $portfolio = new Portfolio();
            $portfolio->created_by_id = Auth::id();
            $portfolio->title = $request->title;
            $portfolio->image = $imageName;
            $portfolio->description = $request->description;
            $portfolio->save();

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $portfolio
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
            if (!request()->user()->tokenCan('portfolio:show')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $portfolio = Portfolio::findOrFail($id);

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $portfolio
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
     * @param int $id
     * @param \App\Http\Requests\Portfolio\UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        try {
            if (!request()->user()->tokenCan('portfolio:update')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $portfolio = Portfolio::findOrFail($id);

            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = Str::uuid() . '.' . $request->image->extension();
                Storage::disk('public')->putFileAs("media/portfolio/", $request->image, $imageName);
            }

            if (Storage::disk('public')->exists("media/portfolio/{$portfolio->image}")) {
                Storage::disk('public')->delete("media/portfolio/{$portfolio->image}");
            }

            $portfolio->title = $request->title;
            $portfolio->image = $imageName;
            $portfolio->description = $request->description;
            $portfolio->save();

            return response()->json([
                'apiVersion' => '1.0',
                'data' => $portfolio
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
            if (!request()->user()->tokenCan('portfolio:delete')) {
                throw new AuthorizationException('User does not have the required permission');
            }

            $portfolio = Portfolio::findOrFail($id);

            if (Storage::disk('public')->exists("media/portfolio/{$portfolio->featured_image}")) {
                Storage::disk('public')->delete("media/portfolio/{$portfolio->featured_image}");
            }

            return response()->json([
                'apiVersion' => '1.0',
                'data' => [
                    'deleted' => $portfolio->delete() ? true : false
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
