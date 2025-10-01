<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo(\Illuminate\Http\Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        abort(response()->json([
            'status' => false,
            'message' => 'Unauthorized - Invalid or missing token'
        ], 401));
    }
}
