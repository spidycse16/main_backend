<?php

namespace App\Http\API;

use Validator;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request) {

        $validator= Validator::make($request->all(),[
            'name'=> 'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password'

        ]);

        if($validator->fails()){
            $response =[
                'Success'=>'false',
                'message'=>$validator->errors()
            ];
            return response()->json($response,400);
        }
        $input=$request->all();
      $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $token = $user->createToken('MyApp')->plainTextToken;

    $success['token'] = $token;
        $success['name']=$user->name;
        $response=[
            'success'=> true,
            'data'=>$success,
            'message'=>'User register Successfully'
        ];
        return response()->json($response,200);
    }

    public function login(Request $request){
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user=Auth::user();
            $success['token']=$user->createToken('MyApp')->plainTextToken;
            $success['name']=$user->name;
            $response=[
                'success'=> true,
                'data'=>$success,
                'message'=>'User login Successfully'
            ];
            return response()->json($response,200);

        }
        else{
            $response = [
                'sucess'=>false,
                'message' => 'Unauthorised'
            ];
            return response()->json($response); 
        }
    }
}
