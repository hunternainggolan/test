<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'address' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
        $arrPrice = [10000, 20000, 30000, 40000, 50000, 100000];
        $key = array_rand($arrPrice);
        $price = $arrPrice[$key];
        $customer = auth('customer')->user();
        $data = [
            'name'     => $request->name,
            'customer_id' => $customer->id,
            'price'    => $price,
            'address' => $request->address,
            'payment_status' => 'waiting'
        ];
        $order = Order::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Order created successfully',
            'data' => $order,
        ], 201);
    }

    public function myorder()
    {
        $customer = auth('customer')->user();
        $order = Order::with(['customer', 'driver'])->where('customer_id', $customer->id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Get My Order successfully',
            'data' => $order,
        ], 201);
    }
    public function orderbycustomer($id)
    {

        $order = Order::with(['customer', 'driver'])->where('customer_id', $id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Get Order By Customer successfully',
            'data' => $order,
        ], 201);
    }
    public function allorder()
    {
        $order = Order::with(['customer', 'driver'])->get();
        return response()->json([
            'status' => true,
            'message' => 'Get All Order successfully',
            'data' => $order,
        ], 201);
    }
    public function getdelivery()
    {
        $status = ['waiting', 'shipping'];
        $order = Order::whereIn('delivery_status', $status)->get();
        return response()->json([
            'status' => true,
            'message' => 'Get Ready to Deliver successfully',
            'data' => $order,
        ], 201);
    }

    public function delivery(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:shipping,delivered,cancelled'
        ]);
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Order not found'], 404);
        }
        $order->delivery_status = $request->status;
        $driver = auth('driver')->user();
        if ($request->status == 'delivered') {
            $order->delivery_date = date('Y-m-d H:i:s');
        } else if ($request->status == 'shipping') {
            $order->driver_id = $driver->id;
        }
        $order->save();
        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully',
            'data' => $order
        ]);
    }
    public function paymentwaiting()
    {
        $customer = auth('customer')->user();
        $order = Order::where('payment_status', 'waiting')->where('customer_id', $customer->id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Get Un-Paid Order successfully',
            'data' => $order,
        ], 201);
    }
}
