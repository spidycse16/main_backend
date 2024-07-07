<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function confirmOrder(Request $request)
    {
        // $request->validate([
        //     'shop_id' => 'required|exists:start_new_businesses,id',
        //     'items' => 'required|array',
        //     'items.*.id' => 'required|exists:items,id',
        // ]);

        $shopId = $request->input('shop_id');
        $items = $request->input('items');

        foreach ($items as $itemData) {
            $itemId = $itemData['id'];
            $item = DB::select("SELECT * FROM items WHERE id = ?", [$itemId]);

            if ($item) {
                $item = $item[0];
                DB::insert("INSERT INTO orders (ShopId, ItemName, ItemPrice, Image, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)", [
                    $shopId,
                    $item->ItemName,
                    $item->ItemPrice,
                    $item->Image,
                    now(),
                    now(),
                ]);
            }
        }

        Log::info('Items inserted into previous_orders');
        return response()->json(['message' => 'Order confirmed and items moved to previous orders.'], 200);
    }
}