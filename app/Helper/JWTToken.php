<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken{

     public static function CreateToken($userEmail,$userId){ 
          $key=env('JWT_SECRET');
          $payload=[
               'iss'=>'laravel-token',
               'iat'=>time(),
               'exp'=>time()+3600,
               'email'=>$userEmail,
               'id'=>$userId
          ];

          $jwt=JWT::encode($payload,$key,'HS256');
          return $jwt;
     }
     public static function CreateTokenForResetPassword($userEmail){
          
   
          $key=env('JWT_SECRET');
          $payload=[
               'iss'=>'laravel-token',
               'iat'=>time(),
               'exp'=>time()+300,
               'email'=>$userEmail,
               '$userId'=>'0'

          ];

          $jwt=JWT::encode($payload,$key,'HS256');
          return $jwt;
     }

     public static function verifyToken($token){
          
        try {
          if($token==null){
            return 'unauthorized';
          }else{

            $key=env('JWT_SECRET');
            $payload=JWT::decode($token,new Key($key,'HS256'));
            return $payload;
          }
            
        } catch (\Throwable $th) {
         return 'unauthorized';
        }
     }
}