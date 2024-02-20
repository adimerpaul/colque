<?php

namespace App\Http\Middleware;

use App\Patrones\Rol;
use Closure;

class ClienteLab
{
    public function handle($request, Closure $next)
    {
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::ClienteLab, Rol::Laboratorio];
        if (auth()->user()) {
            if (auth()->user()->hasRol($roles))
                return $next($request);
            else abort(403);
        } else return redirect()->route('home');
    }
}