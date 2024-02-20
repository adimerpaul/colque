<?php

namespace App\Listeners;

use App\Events\AccionCompletaVenta;
use App\Models\HistorialVenta;
use App\Patrones\Fachada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegistrarHistorialVenta
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccionCompletaVenta  $event
     * @return void
     */
    public function handle(AccionCompletaVenta $event)
    {
        $historial= new HistorialVenta();
        $historial->accion = $event->accion;
        $historial->observacion = $event->observacion;
        $historial->venta_id = $event->venta_id;
        $historial->users_id = auth()->user()->id;
        $historial->save();
    }
}
