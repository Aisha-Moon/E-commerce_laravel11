<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pos\UserController;

Route::post('/user-registration', [UserController::class, 'signup']);
Route::post('/user-login', [UserController::class, 'login']);
