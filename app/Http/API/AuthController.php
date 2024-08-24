<?php

namespace App\Http\API;

use Validator;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8',
                'c_password' => 'required|same:password',
                'phone' => 'nullable|string|max:20',
                'image' => 'nullable|string|max:255',
                'is_shop_owner' => 'nullable|boolean',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 400);
            }
    
            $input = $request->only(['name', 'email', 'password', 'phone', 'image', 'is_shop_owner']);
    
            DB::table('users')->insert($input);
    
            return response()->json([
                'success' => true,
                'data' => $input, // Return the input data as the created user data
                'message' => 'User registered successfully'
            ], 201);
    
        } catch (\Illuminate\Database\QueryException $ex) {
            $errorCode = $ex->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already exists'
                ], 400);
            }
    
            Log::error('Database Query Exception: ' . $ex->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while registering the user'
            ], 500);
    
        } catch (\Exception $ex) {
            Log::error('General Exception: ' . $ex->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }

    /**
     * Log in a user.
     */
    public function login(Request $request) {
        try {
            $user = User::where('email', $request->email)->first();
    
            if ($user && $user->password === $request->password) {
                // Password matches, log the user in
                Auth::login($user);
    
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'image' => $user->image,
                        'is_shop_owner' => $user->is_shop_owner,
                    ],
                    'message' => 'User logged in successfully'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
    
        } catch (\Exception $ex) {
            Log::error('Login Exception: ' . $ex->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login'
            ], 500);
        }
    }
    

    /**
     * Get user details by ID.
     */
    public function getUserById(Request $request, $id) {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 400);
            }

            $user = User::find($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'image' => $user->image,
                    'is_shop_owner' => $user->is_shop_owner,
                ],
                'message' => 'User retrieved successfully'
            ], 200);

        } catch (\Exception $ex) {
            Log::error('Get User Exception: ' . $ex->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the user'
            ], 500);
        }
    }
}
