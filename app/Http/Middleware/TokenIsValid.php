<?php

namespace App\Http\Middleware;

use App\Models\Token;
use App\Patrones\Fachada;
use Closure;

class TokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $actualDate = Fachada::getFecha()->format("Y-m-d");
        $token = Token::where('fecha_expiracion', '>=', $actualDate)->orderByDesc('id')->first();
        if (is_null($token)) {
            return response()->json(['error' => 'Token no valido, el token de impuestos nacionales ha expirado'], 401);
        }
        return $next($request);
    }
}
