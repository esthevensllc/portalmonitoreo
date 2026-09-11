<?php

namespace AMovil\Auth\Shared\Infrastructure\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationMiddleware
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = backpack_auth()->user();
        // dd($user);
        if($user === null){
            if ($request->acceptsJson()) {
                return response()
                ->json([
                    "message" => "Unauthorized"
                ], 401);
            } else {
                return redirect("login");
            }
        }

        return $next($request);
    }
}
