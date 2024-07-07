<?php

namespace App\Http\API;

use App\Models\User;
use App\Models\Image;
use App\Models\Items;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ItemsController extends Controller
{
    public function NewItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ShopId' => 'required|integer|exists:start_new_businesses,id',
            'ItemName' => 'required',
            'ItemPrice' => 'required|string',
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

        $newItem = new Items;
        $newItem->ShopId = $request->input('ShopId');
    $newItem->ItemName = $request->input('ItemName');
    $newItem->ItemPrice = $request->input('ItemPrice');

    $newItem->Image = $imageName;
    $newItem->save();


        $response = [
            'success' => true,
           'data' => $newItem,
           'path'=> asset('uploads/'.$imageName),
            'message' => 'Item started successfully'
        ];

        return response()->json($response, 200);
    }

    public function getItems()
    {
        // Retrieve and return all Itemes from the database
        $Itemes = Items::all();
        foreach ($Itemes as $Item) {
            $Item->image_url = asset('uploads/' . $Item->Image);
            $Item->token = (string) Str::uuid();
        }
        return response()->json($Itemes, 200);
    }
}
