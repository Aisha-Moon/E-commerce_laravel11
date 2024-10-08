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
        $token=$request->cookie('token');
        $decode=JWTToken::VerifyToken($token);
        if($decode=='unauthorized'){
            return redirect('/userLogin');
        }else{
            $request->headers->set('email', $decode->email);
            $request->headers->set('id', $decode->id);
            return $next($request);
        }
       
    }
}


