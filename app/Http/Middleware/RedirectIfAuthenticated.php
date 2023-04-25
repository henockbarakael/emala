<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {

            if( Auth::guard($guard)->check() && Auth::user()->role_name == "Root"){
                return redirect()->route('root.dashboard');
            }
            elseif( Auth::guard($guard)->check() && Auth::user()->role_name == "Admin"){
                return redirect()->route('admin.dashboard');
            }
            elseif( Auth::guard($guard)->check() && Auth::user()->role_name == "Manager"){
                return redirect()->route('manager.dashboard');
            }
            elseif( Auth::guard($guard)->check() && Auth::user()->role_name == "Cashier"){
                return redirect()->route('cashier.dashboard');
            }
        }

        return $next($request);
    }
}
