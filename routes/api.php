<?php

use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Note: in every protected request send token in bearer and set header like this
// Accept application/json 
// Authorization Beare Bearer 5|2jvPL27QE9Okk1lvNCQEQXVgix5MTakR88ajTuBbc1144221
// add these two above fields in header other wise it will not work

Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'loginuser']);
Route::post('/logout',[UserController::class,'logoutuser'])->middleware('auth:sanctum');
Route::get('/loginuser',[UserController::class,'loginuser_details'])->middleware('auth:sanctum');
Route::post('/changepassword',[UserController::class,'change_password'])->middleware('auth:sanctum');

Route::post('/send_email',[ResetPasswordController::class,'send_email']);

// Route::middleware(['auth:sanctum'])->group(function(){
//     Route::post('/logout',[UserController::class,'logoutuser']);
// });