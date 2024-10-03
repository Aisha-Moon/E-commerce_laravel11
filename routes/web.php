<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pos\UserController;

Route::post('/user-registration', [UserController::class, 'signup']);
Route::post('/user-login', [UserController::class, 'login']);
Route::post('/send-otp', [UserController::class, 'sendOtpCode']);
Route::post('/verify-otp', [UserController::class, 'verifyOtpCode']);
Route::post('/reset-password', [UserController::class, 'ResetPass'])->middleware('tokenVerify');
