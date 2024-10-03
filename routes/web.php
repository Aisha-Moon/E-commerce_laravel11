<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pos\UserController;
use App\Http\Controllers\DashboardController;

Route::post('/user-registration', [UserController::class, 'signup']);
Route::post('/user-login', [UserController::class, 'login']);
Route::post('/send-otp', [UserController::class, 'sendOtpCode']);
Route::post('/verify-otp', [UserController::class, 'verifyOtpCode']);
Route::post('/reset-password', [UserController::class, 'ResetPass'])->middleware('tokenVerify');


Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware('tokenVerify');
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware('tokenVerify');

