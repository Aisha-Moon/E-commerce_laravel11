<?php

namespace App\Http\Controllers\pos;

use Exception; 
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'mobile' => 'required|numeric',
                'password' => 'required|string|min:4'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation error', $validator->errors());
            }

            $user = User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);

            return $this->sendResponse($user, 'User created successfully');

        } catch (Exception $e) { 
            return $this->sendError('An error occurred', $e->getMessage(), 500); 
         }
    }

    public function login(Request $request){
      try {
        $validateUser=Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
              );
            if($validateUser->fails()){

                return $this->sendError('Validation error', $validateUser->errors());

            }
            $user=User::where('email',$request->input('email'))
                      ->where('password',$request->input('password'))->count();

            if($user){
                $token = JWTToken::generateToken($request->input('email'));
                return $this->sendResponse([
                  
                    'token' => $token,
                ], 'User login successfully');
                
            }else{
                return $this->sendError('failed','User not found');
            }
      } catch (Exception $e) {
        return $this->sendError('An error occurred', $e->getMessage(), 500); 

      }
    }

    public function sendOtpCode(Request $request){
        $email=$request->input('email');
        $otp=rand(1000,9999);
        $user=User::where('email',$email)->count();
        if($user){
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email',$email)->update(['otp'=>$otp]);
            return $this->sendResponse($email, 'OTP sent successfully');
        }else{
            return $this->sendError('failed','User not found');
        }
    }

    public function verifyOtpCode(Request $request){
        $email=$request->input('email');
        $otp=$request->input('otp');

        $user=User::where('email',$email)->where('otp',$otp)->count();
        if($user){
            User::where('email',$email)->update(['otp'=>0]);
            $token=JWTToken::generateTokenForReset($email);
            return $this->sendResponse($token, 'OTP verified successfully');
        }else{
            return $this->sendError('failed','Invalid OTP');
        }

    }

    public function ResetPass(Request $request){
        try{
            $email=$request->header('email');
            $password=$request->input('password');
            User::where('email',$email)->update(['password'=>$password]);
            return $this->sendResponse($email, 'Password reset successfully');
    
        }catch(Exception $e){
            return $this->sendError('An error occurred', $e->getMessage(), 500);
        }
    }
}
