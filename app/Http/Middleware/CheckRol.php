<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Patrones\Permiso;

class CheckRol
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
        $roles = array_slice(func_get_args(), 2);
        if(auth()->user()->hasRol($roles))
        {
            
            return $next($request);
        }
        return response()->view("errors.403", [], 403);
    }
}
