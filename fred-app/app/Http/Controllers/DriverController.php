<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    //
    public function index()
    {
        $customers = Driver::all();
        return response()->json([
            'status' => true,
            'message' => 'Get All Driver',
            'data' => $customers
        ]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:100|unique:drivers,email',
            'register_number' => 'required|string|max:10',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Driver::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'register_number' => $request->register_number
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Driver registered successfully',
            'data' => $driver,
        ], 201);
    }
}
