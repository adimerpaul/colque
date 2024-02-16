<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'registrar-pesaje',
        'editar-pesaje',
        'satisfaccion-cliente',
        'actualizar-despacho',
        'actualizar-despacho-venta',
        'actualizar-retiro',
        'autenticar-cliente',
        'cambiar-pass-cliente',
        'registrar-cliente',
        'editarar-cliente',
        'actualizar-laboratorio-cliente',
    ];
}
