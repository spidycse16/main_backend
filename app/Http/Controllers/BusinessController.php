<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function createStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ShopName' => 'required|string',
            'ShopLocation' => 'required',
            'ShopType' => 'required|string',
            'PhoneNumber' => 'required',
            'Image' => 'nullable|image|mimes:jpg,jpeg,png', // Optional image validation
            'user_id' => 'nullable|integer|exists:users,id', // Optional user_id validation
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()->toArray()
            ];
            return response()->json($response, 400);
        }

        $imageName = null;
        $img = $request->file('Image');

        if ($img) {
            $ext = $img->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $img->move(public_path('uploads'), $imageName);
        }

        $data = [
            'ShopName' => $request->input('ShopName'),
            'ShopLocation' => $request->input('ShopLocation'),
            'ShopType' => $request->input('ShopType'),
            'PhoneNumber' => $request->input('PhoneNumber'),
            'Image' => $imageName,
            'user_id' => $request->input('user_id') // Add user_id to the data
        ];

        DB::table('start_new_businesses')->insert($data);

        $response = [
            'success' => true,
            'message' => 'Shop created successfully',
            'path' => $imageName ? asset('uploads/' . $imageName) : null, // Conditional URL
        ];

        return response()->json($response, 201);
    }

    public function getBusinesses()
    {
        $businesses = DB::table('start_new_businesses')
            ->select([
                'start_new_businesses.*',
                DB::raw('CONCAT("uploads/", Image) AS image_url'),
            ])
            ->get();

        foreach ($businesses as $business) {
            $business->token = (string) Str::uuid();
        }

        return response()->json($businesses, 200);
    }

    public function NewItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ShopId' => 'required|integer|exists:start_new_businesses,id',
            'ItemName' => 'required',
            'ItemPrice' => 'required|string',
            'Image' => 'nullable|image|mimes:jpg,jpeg,png', // Optional image validation
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 400);
        }

        $imageName = null;
        $img = $request->file('Image');

        if ($img) {
            $ext = $img->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;

            $uploadPath = public_path('uploads');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $img->move($uploadPath, $imageName);
        }

        $sql = "INSERT INTO items (ShopId, ItemName, ItemPrice, Image)
                VALUES (?, ?, ?, ?)";
        $insertedId = DB::insert($sql, [
            $request->input('ShopId'),
            $request->input('ItemName'),
            $request->input('ItemPrice'),
            $imageName,
        ]);

        $newItem = DB::table('items')->find($insertedId);

        $response = [
            'success' => true,
            'data' => $newItem,
            'path' => $imageName ? asset('uploads/' . $imageName) : null, // Conditional URL
            'message' => 'Item added successfully',
        ];

        return response()->json($response, 201);
    }

    public function getItems()
    {
        $items = DB::table('items')
            ->select([
                'items.*',
                DB::raw('CONCAT("uploads/", Image) AS image_url'),
            ])
            ->get();

        foreach ($items as $item) {
            $item->token = (string) Str::uuid();
        }

        return response()->json($items, 200);
    }

    public function getShopByUserId($id)
{
    // Validate the user_id input
    $validator = Validator::make(['user_id' => $id], [
        'user_id' => 'required|integer|exists:start_new_businesses,user_id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->toArray()
        ], 400);
    }

    // Retrieve the shop data by user_id
    $shop = DB::table('start_new_businesses')
        ->where('user_id', $id)
        ->select([
            'start_new_businesses.*',
            DB::raw('CONCAT("uploads/", Image) AS image_url'),
        ])
        ->first();

    if (!$shop) {
        return response()->json([
            'success' => false,
            'message' => 'Shop not found'
        ], 404);
    }

    $shop->token = (string) Str::uuid();

    return response()->json([
        'success' => true,
        'data' => $shop,
    ], 200);
}

}
