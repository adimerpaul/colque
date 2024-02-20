<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnulacionMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $factura = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($factura)
    {
        $this->factura = $factura;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.anulacion_notificacion')
            ->subject("Factura anulada")
            ->with(["factura" => $this->factura]);
    }
}
