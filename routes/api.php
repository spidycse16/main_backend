<?php

use App\Http\Controllers\BusinessController;
use Illuminate\Http\Request;
use App\Http\API\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\API\ItemsController;
use App\Http\API\StartNewBusinessController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MarkerPlaceController;

Route:: middleware('auth:sanctum')->get('/user',function(Request $request){
    return $request->user();
});


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('user/{id}', [AuthController::class, 'getUserById']);
//New Business 
// Route::get('getBusinesses', function () {
//     $controller = new StartNewBusinessController();
//     return $controller->getBusinesses();
// });
//not working
//Route::post('start',[StartNewBusinessController::class,'NewShop']);

//Market Place
//not working
//Route::post('sell',[MarketPlaceController::class,'NewProduct']);

// Route::get('getProducts', function () {
//     $controller = new MarketPlaceController();
//     return $controller->getProducts();
// });

Route::post('items',[BusinessController::class,'NewItem']);

// Route::get('getItems', function () {
//     $controller = new ItemsController();
//     return $controller->getItems();
// });


//new routes
//search api //working
Route::get('search/{text}',[ProductController::class,'search']);
//filter api//working
Route::get('filter/price-high-to-low', [ProductController::class, 'filterPriceHighToLow']);
Route::get('filter/price-low-to-high', [ProductController::class, 'filterPriceLowToHigh']);
Route::get('filter/rating-high-to-low', [ProductController::class, 'filterRatingHighToLow']);
//all item api// working //fromm marketplace table
Route::get('all-item',[ProductController::class,'allItem']);

//edit api

Route::put('edit/{id}', [ProductController::class, 'update']);

//Orders//working

Route::post('/confirm-order', [OrderController::class, 'confirmOrder']);

//New shop api
//start new shop //working
Route::post('/start', [BusinessController::class, 'createStore']);
//add item in the marketplace //working
Route::post('/sell', [MarkerPlaceController::class, 'newSell']);
//show item from the marketplace
Route::get('/getProducts',[MarkerPlaceController::class,'getProducts']);
//show item to seller what are the orders
Route::get('/ordered-items/{shop_id}',[OrderController::class,'orderedItems']);
//get all the shops
Route::get('/getBusinesses',[BusinessController::class,'getBusinesses']);
//get one shop
Route::get('/getShopByUserId/{id}', [BusinessController::class, 'getShopByUserId']);
//get items from item table
Route::get('/getItems',[BusinessController::class,'getItems']);
//get orders by user id
Route::get('/my-orders/{user_id}',[OrderController::class,'myOrders']);
//delte by shop id
Route::delete('/delete-orders/{shopId}',[OrderController::class,'deleteOrders']);
