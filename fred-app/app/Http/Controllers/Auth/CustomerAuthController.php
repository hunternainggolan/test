<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerAuthController extends Controller
{
    //

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $customer = Customer::where('email', $credentials['email'])->first();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Email not found'
            ], 404);
        }

        // cek password
        if (!Hash::check($credentials['password'], $customer->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Incorrect password'
            ], 401);
        }
        if (!$token = auth('customer')->attempt($credentials)) {
            return response()->json(['error' => 'Unautorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public function profile()
    {
        return response()->json(auth('customer')->user());
    }
    public function logout(Request $request)
    {
        auth('admin')->logout();
        return response()->json(['status' => true, 'message' => 'Customer logged out successfully']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => true,
            'message' => 'Login successfully',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('customer')->factory()->getTTL() * 60
        ]);
    }
}
