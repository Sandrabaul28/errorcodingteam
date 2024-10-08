<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class user
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()) {
            $role_name = roles::where('id', auth()->user()->roles)->first();
            if(Auth::user() && $role->role_name == 'user') {
                return $next($request);
            }
        }
        return redirect()->route('login');
    }
}
