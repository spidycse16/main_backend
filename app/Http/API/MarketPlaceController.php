<?php

namespace App\Http\API;

use App\Models\User;
use App\Models\Image;
use App\Models\MarketPlace;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MarketPlaceController extends Controller
{
    public function NewProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ItemName' => 'required|string',
            'ItemPrice' => 'required',
            'Image' => 'required|image|mimes:jpg,jpeg,png',
            'ProductInformation' => 'required|string',
            'DeliveryInformation' => 'required',
           
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $img=$request->Image;
        $ext=$img->getClientOriginalExtension();
        $imageName=time().'.'.$ext;
        $img->move(public_path().'/uploads',$imageName);

        $newProduct = new MarketPlace;
    $newProduct->ItemName = $request->input('ItemName');
    $newProduct->ItemPrice = $request->input('ItemPrice');
    $newProduct->Image = $imageName;
    $newProduct->ProductInformation= $request->input('ProductInformation');
    $newProduct->DeliveryInformation = $request->input('DeliveryInformation');
    
    $newProduct->save();
        $response = [
            'success' => true,
           'data' => $newProduct,
           'path'=> asset('uploads/'.$imageName),
            'message' => 'Product uploaded successfully'
        ];

        return response()->json($response, 200);
    }

    public function getProducts()
    {
        // Retrieve and return all Productes from the database
        $Productes = MarketPlace::all();
        foreach ($Productes as $Product) {
            $Product->image_url = asset('uploads/' . $Product->Image);
            $Product->token = (string) Str::uuid();
        }
        return response()->json($Productes, 200);
    }
}
