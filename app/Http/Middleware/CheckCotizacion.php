<?php

namespace App\Http\Middleware;

use App\Patrones\Fachada;
use App\Patrones\Permiso;
use Closure;
use Illuminate\Http\Request;

class CheckCotizacion
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
        if(Fachada::tieneCotizacion() or Permiso::esAdmin())
            return $next($request);

        return redirect(route('home'));
    }
}
