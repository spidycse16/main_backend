<?php

use Illuminate\Http\Request;
use App\Http\API\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\API\MarketPlaceController;
use App\Http\API\ItemsController;
use App\Http\API\StartNewBusinessController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route:: middleware('auth:sanctum')->get('/user',function(Request $request){
    return $request->user();
});


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

//New Business 
Route::get('getBusinesses', function () {
    $controller = new StartNewBusinessController();
    return $controller->getBusinesses();
});
Route::post('start',[StartNewBusinessController::class,'NewShop']);

//Market Place
Route::post('sell',[MarketPlaceController::class,'NewProduct']);

Route::get('getProducts', function () {
    $controller = new MarketPlaceController();
    return $controller->getProducts();
});

Route::post('items',[ItemsController::class,'NewItem']);

Route::get('getItems', function () {
    $controller = new ItemsController();
    return $controller->getItems();
});


//new routes
//search api
Route::get('search/{text}',[ProductController::class,'search']);
//filter api
Route::get('filter/price-high-to-low', [ProductController::class, 'filterPriceHighToLow']);
Route::get('filter/price-low-to-high', [ProductController::class, 'filterPriceLowToHigh']);
Route::get('filter/rating-high-to-low', [ProductController::class, 'filterRatingHighToLow']);
//all item api
Route::get('all-item',[ProductController::class,'allItem']);

//edit api

Route::put('edit/{id}', [ProductController::class, 'update']);

//Orders

Route::post('/confirm-order', [OrderController::class, 'confirmOrder']);