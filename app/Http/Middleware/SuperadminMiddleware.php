<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperadminMiddleware
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
        if(auth()->check() && auth()->user()->secured){
        //if(auth()->check() && auth()->user()->secured && auth()->user()->roles->contains('name', 'superadmin')){
            return $next($request);
        }
        
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
