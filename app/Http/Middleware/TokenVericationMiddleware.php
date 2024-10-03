<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVericationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token=$request->header('token');
        $result=JWTToken::VerifyToken($token);

        if($result=='unauthorized'){
            return response()->json(['message'=>'unauthorized'],401);
        }else{

// Example of setting a value for internal use
$request->headers->set('email', $result);
            return $next($request);
            

        }
    }
}
