<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    //
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    protected function notFound()
    {
        return response()->json([
            'status' => '404',
            'error' => 'Not Found'
        ], 404);
    }

    protected function serverError()
    {
        return response()->json([
            'error' => 'Server Error',
        ], 500);
    }
}
