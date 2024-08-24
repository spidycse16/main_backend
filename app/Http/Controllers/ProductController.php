<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function search($text)
    {
        //$text = $request->query('query');

        $query = "SELECT * FROM market_places 
                  WHERE ItemName LIKE ? 
                  OR ProductInformation LIKE ?";

        $products = DB::select($query, ["%{$text}%", "%{$text}%"]);

        return response()->json($products);
    }

    public function filterPriceHighToLow(Request $request)
    {
        $query = "SELECT * FROM market_places  ORDER BY CAST(ItemPrice AS DECIMAL(10, 2)) DESC";
        $products = DB::select($query);
        return response()->json($products);
    }

    public function filterPriceLowToHigh(Request $request)
    {
        $query = "SELECT * FROM market_places  ORDER BY CAST(ItemPrice AS DECIMAL(10, 2)) ASC";
        $products = DB::select($query);
        return response()->json($products);
    }

    public function filterRatingHighToLow(Request $request)
    {
        $query = "SELECT * FROM market_places  ORDER BY Rating DESC";
        $products = DB::select($query);
        return response()->json($products);
    }
    public function allItem()
{
    $products = DB::table('market_places')->get();
    return response()->json($products);
}
public function update(Request $request, $user_id)
{
    // Validate the input data
    $request->validate([
        'ItemName' => 'required|string|max:255',
        'ItemPrice' => 'required|string|max:255',
        'Image' => 'nullable|string|max:255',
        'ProductInformation' => 'nullable|string|max:255',
        'DeliveryInformation' => 'nullable|string|max:255'
    ]);

    // Prepare the data for updating
    $data = [
        'ItemName' => $request->input('ItemName'),
        'ItemPrice' => $request->input('ItemPrice'),
        'Image' => $request->input('Image'),
        'ProductInformation' => $request->input('ProductInformation'),
        'DeliveryInformation' => $request->input('DeliveryInformation'),
        'updated_at' => now()
    ];

    // Update the record in the database based on user_id
    $affectedRows = DB::table('market_places')
        ->where('user_id', $user_id) // Use user_id instead of id
        ->update($data);

    // Check if the update was successful
    if ($affectedRows === 0) {
        return response()->json([
            'success' => false,
            'message' => 'No record found for the given user_id or no changes made'
        ], 404);
    }

    // Retrieve the updated record
    $product = DB::table('market_places')
        ->where('user_id', $user_id) // Use user_id instead of id
        ->first();

    return response()->json([
        'success' => true,
        'data' => $product
    ]);
}

}