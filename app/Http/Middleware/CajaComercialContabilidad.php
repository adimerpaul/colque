<?php

namespace App\Http\Middleware;

use App\Patrones\Rol;
use Closure;

class CajaComercialContabilidad
{
    public function handle($request, Closure $next)
    {
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Caja, Rol::Comercial, Rol::Contabilidad, Rol::Operaciones, Rol::Rrhh];
        if (auth()->user()) {
            if (auth()->user()->hasRol($roles))
                return $next($request);
            else abort(403);
        } else return redirect()->route('home');
    }
}
