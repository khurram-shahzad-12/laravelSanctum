<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Message;
use App\Models\User;
use Carbon\Carbon;
use Pest\Support\Str;


class ResetPasswordController extends Controller
{
    public function send_email(Request $request){
        $request->validate([
            'email'=>'required|email',
        ]);
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response([
                'message'=>'email does not exit',
                'status'=>'failed'
            ],404);
        }
        $token = Str::random(60);
        ResetPassword::create([
            'email'=>$request->email,
            'token'=>$token,
            'created_at'=>Carbon::now()
        ]);
        // dump("http://127.0.0.1:3000/api/user/reset/".$token);

        Mail::send('resetpassword', ['token' => $token], function(Message $message) use ($request) {
            $message->subject('Reset Your Password');
            $message->to($request->email);
        });

        return response([
            'message'=>'password reset email has been sent, check your email',
            'status'=>'success',
        ],200 );
    }
}
