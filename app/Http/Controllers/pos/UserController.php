<?php

namespace App\Http\Controllers\pos;

use Exception; 
use App\Models\User;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
