<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function createUser(Request $request){
        try {
            $validate_user = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' =>'required'
            ]);

            if ($validate_user->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validate_user->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginUser(Request $request){
        try {
            $validate_user = Validator::make($request->all(),[
                'email' => 'required|email|',
                'password' =>'required'
            ]);

            if ($validate_user->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validate_user->errors()
                ], 401);
            }
            if (!Auth::attempt($request->only(['email','password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::query()->where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User Login Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);

        }catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
