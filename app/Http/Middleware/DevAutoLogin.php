<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DevAutoLogin
{
    /**
     * Handle an incoming request for development environments.
     * Automatically logs in the first user if no one is authenticated.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run in local/development environment
        if (app()->environment(['local', 'development']) && !Auth::check()) {
            $user = User::where('tenant_id', 1)->first();
            
            if ($user) {
                Auth::login($user);
                session()->put('tenant_id', $user->tenant_id);
            }
        }

        return $next($request);
    }
}
