<?php

namespace App\Http\API;

use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\startNewBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StartNewBusinessController extends Controller
{
    public function NewShop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ShopName' => 'required|string',
            'ShopLocation' => 'required',
            'ShopType' => 'required|string',
            'PhoneNumber' => 'required',
            'Image' => 'required|image|mimes:jpg,jpeg,png'
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

        $newBusiness = new StartNewBusiness;
    $newBusiness->ShopName = $request->input('ShopName');
    $newBusiness->ShopLocation = $request->input('ShopLocation');
    $newBusiness->ShopType = $request->input('ShopType');
    $newBusiness->PhoneNumber = $request->input('PhoneNumber');
    $newBusiness->Image = $imageName;
    $newBusiness->save();


        $response = [
            'success' => true,
           'data' => $newBusiness,
           'path'=> asset('uploads/'.$imageName),
            'message' => 'Business started successfully'
        ];

        return response()->json($response, 200);
    }

    public function getBusinesses()
    {
        // Retrieve and return all businesses from the database
        $businesses = StartNewBusiness::all();
        foreach ($businesses as $business) {
            $business->image_url = asset('uploads/' . $business->Image);
            $business->token = (string) Str::uuid();
        }
        return response()->json($businesses, 200);
    }
}
