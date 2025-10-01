<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'payment_method' => 'required|string|in:cod,transfer,qris',
            'value' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
        $order = Order::where('id', $request->order_id)->first();
        if ($order->payment_status == 'paid') {
            return response()->json([
                'status' => false,
                'message' => 'Order has been paid',
            ], 422);
        }
        if ($order->price != $request->value) {
            return response()->json([
                'status' => false,
                'message' => 'Payment value not match with Order Price',
            ], 422);
        }
        $data = [
            'order_id'     => $request->order_id,
            'payment_method'     => $request->payment_method,
            'value' => $request->value,
        ];
        $payment = Payment::create($data);
        $order->payment_status = 'paid';
        $order->delivery_status = 'waiting';
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Payment successfully',
            'data' => $payment,
        ], 201);
    }
    public function getall()
    {
        $payment = Payment::all();
        return response()->json([
            'status' => true,
            'message' => 'Get Payment successfully',
            'data' => $payment,
        ], 201);
    }
}
