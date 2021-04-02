<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if (!$user->Status) {
                return response()->json(['message' => 'Your account is inactivated by Admin!'], 401);
            }

            return $next($request);
        }


        return response()->json(['message' => 'Please login and try again!'], 401);
    }
}
