<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccionCompletaVenta
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $accion;
    public $observacion;
    public $venta_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($accion, $observacion, $venta_id)
    {
        $this->accion = $accion;
        $this->observacion = $observacion;
        $this->venta_id = $venta_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
