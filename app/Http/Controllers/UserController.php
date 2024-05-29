<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Register function **************
    public function register(Request $request){
        print_r("hellow");
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed',
            'tc'=>'required',
        ]);

        if(User::where('email',$request->email)->first()){
            return response([
                'message'=>'email already exist',
                'status'=>'false',
            ],401);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'tc'=>json_decode($request->tc ),
        ]);
        $token = $user->createToken($request->email)->plainTextToken;
        return response([
            'message'=>'created successfully',
            'status'=>200,
            'token'=>$token,
            'user'=>$user,
        ]);
    }

    // login function 

    public function loginuser(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user = User::where('email',$request->email)->first();

        if($user && Hash::check($request->password,$user->password)){
            $token = $user->createToken($request->email)->plainTextToken;

            return response([
                'token'=>$token,
                'message'=>'login successfully',
                'status'=>'success',
            ],200);
        }
        return response([
            'message'=>'incorrect credentials',
            'status'=>'faild',
        ],401);
    }


     // log out user 
     public function logoutuser(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response([
            'message'=>'logout successfully',
            'status'=>'success',
        ],200);

    }

    // to get user detail 
    public function loginuser_details(){
        $loginuser = auth()->user();
        return response()->json([
            'user'=>$loginuser,
            'message'=>'login user data',
            'status'=>'success',
        ],200);
    }

    
    // to change password  
    public function change_password(Request $request){
        $request->validate([
            'password'=>'required | confirmed',
        ]); 
        $loginuser = auth()->user();
        $loginuser->password = Hash::make($request->password);
        $loginuser->save();
        return response([
            'message'=>'password changed',
            'status'=>'success'
        ],200);
    }



}
