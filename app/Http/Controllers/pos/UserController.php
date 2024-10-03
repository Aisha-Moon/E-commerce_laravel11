<?php

namespace App\Http\Controllers\pos;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Exception; 
use App\Http\Controllers\BaseController;

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
}
