<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'roles' => \App\Http\Middleware\CheckRol::class,
        'cotizacion' => \App\Http\Middleware\CheckCotizacion::class,
        'comercial' => \App\Http\Middleware\Comercial::class,
        'administrador' => \App\Http\Middleware\Administrador::class,
        'contabilidad' => \App\Http\Middleware\Contabilidad::class,
        'contabilidadComercialOperaciones' => \App\Http\Middleware\ContabilidadComercialOperaciones::class,
        'comercialOperaciones' => \App\Http\Middleware\ComercialOperaciones::class,
        'pesaje' => \App\Http\Middleware\Pesaje::class,
        'caja' => \App\Http\Middleware\Caja::class,
        'cajaComercialContabilidad' => \App\Http\Middleware\CajaComercialContabilidad::class,
        'cajaContabilidad' => \App\Http\Middleware\CajaContabilidad::class,
        'operaciones' => \App\Http\Middleware\Operaciones::class,
        'invitado' => \App\Http\Middleware\Invitado::class,
        'contabilidadComercialInvitado' => \App\Http\Middleware\ContabilidadComercialInvitado::class,
        'activos' => \App\Http\Middleware\Activos::class,
        'rrhh' => \App\Http\Middleware\Rrhh::class,//rrhh(asistencia)
        'todos' => \App\Http\Middleware\Todos::class,
        'laboratorio' => \App\Http\Middleware\Laboratorio::class,
        'clienteLab' => \App\Http\Middleware\ClienteLab::class,

    ];
}
