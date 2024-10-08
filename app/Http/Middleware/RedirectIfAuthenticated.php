<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Fetch role name for the authenticated user
                $role = Auth::user()->role->name;  // Adjust based on your role relationship

                // Redirect based on role
                switch ($role) {
                    case 'Admin':
                        return redirect()->route('admin.dashboard');
                    case 'User':
                        return redirect()->route('user.dashboard');
                    case 'Aggregator':
                        return redirect()->route('aggregator.dashboard');
                    default:
                        return redirect('/');  // Fallback route if role is not recognized
                }
            }
        }

        return $next($request);
    }
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            // Redirect user to the correct dashboard based on their role
            $user = Auth::user();
            
            if ($user->role_id === 1) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role_id === 2) {
                return redirect()->route('user.dashboard');
            } elseif ($user->role_id === 3) {
                return redirect()->route('aggregator.dashboard');
            }
        }

        return $next($request);
    }

}
