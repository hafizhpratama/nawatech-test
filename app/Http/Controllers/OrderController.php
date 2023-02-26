<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        // Validate request input
        $request->validate([
            'delivery_address' => 'required|string|max:255',
            'motorcycle' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Create new order
        $order = Order::create([
            'user_id' => Auth::id(),
            'delivery_address' => $request->delivery_address,
            'motorcycle' => $request->motorcycle,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        Log::channel('order')->info('Order created', [
            'user_id' => $request->user()->id,
            'delivery_address' => $request->delivery_address,
            'motorcycle' => $request->motorcycle,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }

    public function cancelOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($order->status != 'pending') {
            return response()->json(['error' => 'Order cannot be cancelled'], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['message' => 'Order cancelled']);
    }

    public function deleteOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted']);
    }

    public function exportToCsv($id)
    {
        $userId = Auth::id();
        $order = Order::where('user_id', $userId)->where('id', $id)->get()->first();

        $csv = Writer::createFromString('');
        $csv->insertOne(['Order ID', 'Delivery Address', 'Motorcycle', 'Quantity', 'Total Price', 'Status']);

        $csv->insertOne([
            $order->id,
            $order->delivery_address,
            $order->motorcycle,
            $order->quantity,
            $order->total_price,
            $order->status,
        ]);

        $filename = 'orders_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];

        return Response::make($csv->getContent(), 200, $headers);
    }

}
