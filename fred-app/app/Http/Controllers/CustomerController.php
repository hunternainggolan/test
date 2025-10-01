<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //
    public function index()
    {
        $customers = Customer::all();
        return response()->json([
            'status' => true,
            'message' => "Get All Customer success",
            'data' => $customers
        ]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:100|unique:customers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Customer registered successfully',
            'data' => $customer,
        ], 201);
    }
}
