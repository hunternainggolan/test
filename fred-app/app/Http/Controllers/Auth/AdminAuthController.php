<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $admin = User::where('email', $credentials['email'])->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Email not found'
            ], 404);
        }

        // cek password
        if (!Hash::check($credentials['password'], $admin->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Incorrect password'
            ], 401);
        }
        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unautorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public function profile()
    {
        return response()->json(auth('admin')->user());
    }
    public function logout()
    {
        auth('admin')->logout();
        return response()->json(['status' => true, 'message' => 'Admin logged out successfully']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => true,
            'message' => 'Login successfully',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }
}
