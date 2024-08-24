<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function confirmOrder(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:start_new_businesses,id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
        ]);

        $shopId = $request->input('shop_id');
        $items = $request->input('items');
        $quantity=$request->input('quantity');
        $user_id=$request->input('user_id');

        if (is_array($items)) {
        foreach ($items as $itemData) {
            $itemId = $itemData['id'];
            $item = DB::select("SELECT * FROM items WHERE id = ?", [$itemId]);

            if ($item) {
                $item = $item[0];
                DB::insert("INSERT INTO orders (ShopId, ItemName, ItemPrice, Image, created_at, updated_at,ItemQuantity,user_id) VALUES (?, ?, ?, ?, ?, ?,?,?)", [
                    $shopId,
                    $item->ItemName,
                    $item->ItemPrice,
                    $item->Image,
                    now(),
                    now(),
                    $quantity,
                    $user_id
                ]);
            }
        }
    }

        Log::info('Items inserted into orders');
        return response()->json(['message' => 'Order confirmed and items moved to previous orders.'], 200);
    }

    public function orderedItems($shop_id)
    {
        //$shop_id = $request->query('shop_id');
        $orders = DB::table('orders')
                    ->where('ShopId', $shop_id)
                    ->get();

        // Return the orders as a JSON response
       // dd($orders);
        return response()->json($orders);
    }

    public function myOrders($user_id)
    {
        //$shop_id = $request->query('shop_id');
        $orders = DB::table('orders')
                    ->where('user_id', $user_id)
                    ->get();

        // Return the orders as a JSON response
        return response()->json($orders);
    }

    public function deleteOrders($shop_id)
    {
        try {
            // Check if the shop exists
            $shopExists = DB::table('start_new_businesses')->where('id', $shop_id)->exists();
    
            if (!$shopExists) {
                return response()->json(['message' => 'Invalid or non-existent shop ID'], 404);
            }
    
            // Delete the orders associated with the shop_id
            $deletedRows = DB::table('orders')->where('ShopId', $shop_id)->delete();
    
            if ($deletedRows === 0) {
                return response()->json(['message' => 'No orders found for this shop ID'], 404);
            }
    
            return response()->json(['message' => 'Orders deleted successfully'], 200);
    
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error deleting orders: ' . $e->getMessage());
    
            return response()->json(['message' => 'Error deleting orders'], 500);
        }
       
    }
}