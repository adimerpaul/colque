<?php

namespace App\Listeners;

use App\Events\AccionCompleta;
use App\Models\Historial;
use App\Patrones\Fachada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegistrarHistorial
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
     * @param  AccionCompleta  $event
     * @return void
     */
    public function handle(AccionCompleta $event)
    {
        $historial= new Historial();
        $historial->fecha = Fachada::getFechaHora();
        $historial->accion = $event->accion;
        $historial->observacion = $event->observacion;
        $historial->formulario_liquidacion_id = $event->formulario_id;
        $historial->users_id = auth()->user()->id;
        $historial->save();
    }
}
