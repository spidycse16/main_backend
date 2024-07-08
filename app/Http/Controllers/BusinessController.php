<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
  if($img)
  {
      $ext = $img->getClientOriginalExtension();
      $imageName = time() . '.' . $ext;
      $img->move(public_path('uploads'), $imageName);
  }
  else
  $imageName="alt";

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
}
