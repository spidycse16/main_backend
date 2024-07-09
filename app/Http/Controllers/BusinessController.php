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
  ]);

  if ($validator->fails()) {
    $response = [
      'success' => false,
      'message' => $validator->errors()->toArray()
    ];
    return response()->json($response, 400);
  }

  $img = $request->file('Image');
      $ext = $img->getClientOriginalExtension();
      $imageName = time() . '.' . $ext;
      $img->move(public_path('uploads'), $imageName);
      
  $data = [
    'ShopName' => $request->input('ShopName'),
    'ShopLocation' => $request->input('ShopLocation'),
    'ShopType' => $request->input('ShopType'),
    'PhoneNumber' => $request->input('PhoneNumber'),
    'Image' => $imageName,
  ];

  DB::table('start_new_businesses')->insert($data);

  $response = [
    'success' => true,
    'message' => 'Shop created successfully',
    'path' => asset('uploads/' . $imageName),
  ];

  return response()->json($response, 201);
}

public function getBusinesses()
{
  $businesses = DB::table('start_new_businesses')
    ->select([
      'start_new_businesses.*', // Select all columns from businesses table
      DB::raw('CONCAT("uploads/", Image) AS image_url'), // Create a virtual column for image URL
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
        'Image' => 'required|image|mimes:jpg,jpeg,png',
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

        // Ensure the uploads directory exists with proper permissions
        $uploadPath = public_path('uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true); // Create directory with permissions
        }

        $img->move($uploadPath, $imageName); // Move the uploaded file
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
        'path' => asset('uploads/' . $imageName),
        'message' => 'Item added successfully',
    ];

    return response()->json($response, 201);
}
public function getItems()
{
    $sql = "SELECT i.*, CONCAT('uploads/', Image) AS image_url
            FROM items AS i";
    $items = DB::select($sql);

    foreach ($items as $item) {
        $item->token = (string) Str::uuid();
    }

    return response()->json($items, 200);
}

}
