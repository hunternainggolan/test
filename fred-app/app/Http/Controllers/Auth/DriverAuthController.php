<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $driver = Driver::where('email', $credentials['email'])->first();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Email not found'
            ], 404);
        }

        // cek password
        if (!Hash::check($credentials['password'], $driver->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Incorrect password'
            ], 401);
        }
        if (!$token = auth('driver')->attempt($credentials)) {
            return response()->json(['error' => 'Unautorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public function profile()
    {
        return response()->json(auth('driver')->user());
    }
    public function logout()
    {
        auth('driver')->logout();
        return response()->json(['status' => true, 'message' => 'Driver logged out successfully']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => true,
            'message' => 'Login successfully',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('driver')->factory()->getTTL() * 60
        ]);
    }
}
