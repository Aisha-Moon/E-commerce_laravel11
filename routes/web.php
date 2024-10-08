<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\pos\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\pos\CategoryController;
use App\Http\Controllers\pos\CustomerController;

Route::post('/user-registration', [UserController::class, 'signup']);
Route::post('/user-login', [UserController::class, 'login']);
Route::post('/send-otp', [UserController::class, 'sendOtpCode']);
Route::post('/verify-otp', [UserController::class, 'verifyOtpCode']);
Route::post('/reset-password', [UserController::class, 'ResetPass'])->middleware('tokenVerify');
Route::get('/logout',[UserController::class,'UserLogout']);
Route::post('/user-update',[UserController::class,'UpdateProfile'])->middleware('tokenVerify');



Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware('tokenVerify');
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware('tokenVerify');
Route::get('/user-profile',[UserController::class,'UserProfile'])->middleware('tokenVerify');
Route::get('/userProfile',[UserController::class,'ProfilePage'])->middleware('tokenVerify');
Route::get('categoryPage', [CategoryController::class, 'page'])->name('categories.page')->middleware('tokenVerify');
Route::get('customerPage', [CustomerController::class, 'page'])->name('customers.page')->middleware('tokenVerify');
Route::get('productPage', [ProductController::class, 'page'])->name('products.page')->middleware('tokenVerify');
Route::get('/invoicePage',[InvoiceController::class,'InvoicePage'])->middleware('tokenVerify');
Route::get('/salePage',[InvoiceController::class,'SalePage'])->middleware('tokenVerify');
Route::get('/reportPage',[ReportController::class,'ReportPage'])->middleware('tokenVerify');




Route::resource('categories', CategoryController::class)->middleware('tokenVerify');
Route::resource('customers', CustomerController::class)->middleware('tokenVerify');
Route::resource('products', ProductController::class)->middleware('tokenVerify');

// Invoice
Route::post("/invoice-create",[InvoiceController::class,'invoiceCreate'])->middleware('tokenVerify');
Route::get("/invoice-select",[InvoiceController::class,'invoiceSelect'])->middleware('tokenVerify');
Route::post("/invoice-details",[InvoiceController::class,'InvoiceDetails'])->middleware('tokenVerify');
Route::post("/invoice-delete",[InvoiceController::class,'invoiceDelete'])->middleware('tokenVerify');


Route::get("/summary",[DashboardController::class,'Summary'])->middleware('tokenVerify');
Route::get("/sales-report/{FormDate}/{ToDate}",[ReportController::class,'SalesReport'])->middleware('tokenVerify');

