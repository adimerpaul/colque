<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccionCompleta
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $accion;
    public $observacion;
    public $formulario_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($accion, $observacion, $formulario_id)
    {
        $this->accion = $accion;
        $this->observacion = $observacion;
        $this->formulario_id = $formulario_id;
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
