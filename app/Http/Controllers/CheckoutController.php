<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        $orders = Order::where('customer_id', $userId)
            ->select('orders.*')
            ->with(['orderItems' => function ($query) {
                $query->select('order_items.*', 'products.name as product_name')
                    ->join('products', 'order_items.product_id', '=', 'products.id');
            }])
            ->get();
    
        return response()->json($orders);
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'total' => 'required|integer',
            'order_items' => 'required|array',
        ]);

        $order = new Order([
            'customer_id' => $request->customer_id,
            'totalPrice' => $request->total,
            'status' => 0,
        ]);

        $order->save();

        foreach ($request->order_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quanlity' => $item['quantity'],
            ]);
        }

        return response()->json([
            'message' => 'Đơn hàng đã được lưu thành công!',
            'order' => $order,
        ], 201);
    }
    public function getAll(){
        $orders = Order::where('status', '!=', -1)
        ->select('orders.*', 'customers.name as customer_name') 
        ->join('customers', 'orders.customer_id', '=', 'customers.id') 
        ->with([
            'orderItems' => function ($query) {
                $query->select('order_items.*', 'products.name as product_name', 
                'products.price as product_price')
                    ->join('products', 'order_items.product_id', '=', 'products.id');
            }
        ])
        ->get();

    return response()->json($orders);
    }
    public function getSucccess(){
        $orders = Order::where('status',  -1)
        ->select('orders.*', 'customers.name as customer_name') 
        ->join('customers', 'orders.customer_id', '=', 'customers.id') 
        ->with([
            'orderItems' => function ($query) {
                $query->select('order_items.*', 'products.name as product_name', 
                'products.price as product_price')
                    ->join('products', 'order_items.product_id', '=', 'products.id');
            }
        ])
        ->get();

    return response()->json($orders);
    }
    public function confirmOrder(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            
            switch ($order->status) {
                case 0:
                    $order->status = 1; 
                    break;
                case 1:
                    $order->status = 2; 
                    break;
                case 2:
                    $order->status = -1;
                    break;
                default:
                    return response()->json(['error' => 'Invalid order status'], 400);
            }
            
            $order->save();
            
            return response()->json(['message' => 'Order status updated successfully'], 200);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function cancelOrder($orderId)
{
    try {
        $order = Order::findOrFail($orderId);

        if ($order->status < 2) {
            $order->status = -1;
            $order->save();
            
            return response()->json(['message' => 'Đơn hàng đã được hủy thành công'], 200);
        } else {
            return response()->json(['error' => 'Không thể hủy đơn hàng này'], 400);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}
