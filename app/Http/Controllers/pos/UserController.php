<?php

namespace App\Http\Controllers\pos;

use Exception; 
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function LoginPage()
    {
        return view('pages.auth.login-page');
    }

    public function RegistrationPage()
    {
        return view('pages.auth.registration-page');
    }

    public function SendOtpPage()
    {
        return view('pages.auth.send-otp-page');
    }

    public function VerifyOTPPage()
    {
        return view('pages.auth.verify-otp-page');
    }

    public function ResetPasswordPage()
    {
        return view('pages.auth.reset-pass-page');
    }

    public function ProfilePage()
    {
        return view('pages.dashboard.profile-page');
    }

    public function signup(Request $request)
    {
        try {
            $user = User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'), // Not hashed
            ]);
            return response()->json([
                'message' => 'User created successfully!',
                'status' => 'success',
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User Registration Failed: ' . $e->getMessage(),
                'status' => 'Failed'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))
            ->where('password', $request->input('password'))
            ->first();

        if ($user !== null) {
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json([
                'message' => 'User logged in successfully!',
                'status' => 'success',
            ], 200)->cookie('token', $token, 60 * 24 * 30, '');
        } else {
            return response()->json([
                'message' => 'Invalid email or password',
                'status' => 'Failed'
            ], 401);
        }
    }

    public function sendOtpCode(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $user = User::where('email', $email)->count();

        if ($user == 1) {
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', $email)->update(['otp' => $otp]);

            return response()->json([
                'message' => 'OTP sent successfully!',
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid email',
                'status' => 'Failed'
            ], 404);
        }
    }

    public function verifyOtpCode(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $user = User::where('email', $email)->where('otp', $otp)->count();

        if ($user == 1) {
            User::where('email', $email)->update(['otp' => 0]);
            $token = JWTToken::CreateTokenForResetPassword($email);

            return response()->json([
                'message' => 'OTP verified successfully!',
                'status' => 'success',
            ], 200)->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'message' => 'Invalid OTP',
                'status' => 'Failed'
            ], 401);
        }
    }

    public function ResetPass(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email', $email)->update(['password' => $password]);
            return response()->json([
                'message' => 'Password reset successfully!',
                'status' => 'success',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Password reset failed: ' . $e->getMessage(),
                'status' => 'Failed'
            ], 500);
        }
    }

    public function UserProfile(Request $request)
    {
        $email = $request->header('email');
        $user = User::where('email', $email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data' => $user
        ], 200);
    }

    public function UserLogout(Request $request)
    {
        return redirect('/userLogin')->withCookie('token', null, -1, '/');
    }

    public function UpdateProfile(Request $request)
    {
        try {
            $email = $request->header('email');
            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');
            $mobile = $request->input('mobile');
            $password = $request->input('password');

            User::where('email', $email)->update([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'mobile' => $mobile,
                'password' => $password // Not hashed
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ], 500);
        }
    }
}