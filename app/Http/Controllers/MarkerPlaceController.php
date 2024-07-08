<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkerPlaceController extends Controller
{
    public function newSell(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'ItemName' => 'required|string',
        'ItemPrice' => 'required',
        'ProductInformation' => 'required|string',
        'DeliveryInformation' => 'required',
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
      $imageName="Image not available";
    
      $data = [
        'ItemName' => $request->input('ItemName'),
        'ItemPrice' => $request->input('ItemPrice'),
        'Image' => $imageName,
        'ProductInformation' => $request->input('ProductInformation'),
        'DeliveryInformation' => $request->input('DeliveryInformation'),
      ];
    
      DB::table('market_places')->insert($data);
    
      $response = [
        'success' => true,
        'message' => 'Shop created successfully',
        'path' => asset('uploads/' . $imageName),
      ];
    
      return response()->json($response, 201);
    }

    public function getProducts()
    {
      $products = DB::table('market_places')->get();
    
      $response = [
        'success' => true,
        'data' => $products,
      ];
    
      return response()->json($response, 200);
    }
}
