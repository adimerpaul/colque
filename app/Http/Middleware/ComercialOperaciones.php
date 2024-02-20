<?php

namespace App\Http\Middleware;

use App\Patrones\Rol;
use Closure;

class ComercialOperaciones
{
    public function handle($request, Closure $next)
    {
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Comercial, Rol::Operaciones];
        if (auth()->user()) {
            if (auth()->user()->hasRol($roles))
                return $next($request);
            else abort(403);
        } else return redirect()->route('home');
    }
}
