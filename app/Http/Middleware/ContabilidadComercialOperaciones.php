<?php

namespace App\Http\Middleware;

use App\Patrones\Rol;
use Closure;

class ContabilidadComercialOperaciones
{
    public function handle($request, Closure $next)
    {
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Contabilidad, Rol::Comercial, Rol::Operaciones, Rol::Rrhh];
        if (auth()->user()) {
            if (auth()->user()->hasRol($roles))
                return $next($request);
            else abort(403);
        } else return redirect()->route('home');
    }
}
