<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccessRevenue
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->user()->permissions->where('name','access_to_revenue_system')->first()){
        //    abort(403,'Unauthorized');
           Auth::logout();
           request()->session()->invalidate();
           request()->session()->regenerateToken();
           return redirect()->route('login')->with("warning","شما صلاحیت ورود به این سیستم را ندارید");
        }

        return $next($request);
        
    }
}
