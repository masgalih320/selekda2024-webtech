<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __invoke()
    {
        if (!Auth::check()) {
            return response()->json([
                'apiVersion' => '1.0',
                'code' => 401,
                'message' => 'Request is missing valid authentication credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'apiVersion' => '1.0',
            'code' => 200,
            'data' => Auth::user()
        ], Response::HTTP_OK);
    }
}
