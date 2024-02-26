<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $comprador;
    public $factura;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($comprador, $factura)
    {
        $this->comprador = $comprador;
        $this->factura = $factura;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'comprador' => $this->comprador,
            'factura' => $this->factura
        ];
        error_log('data'.json_encode($data));
        $carpetaYmd = $this->formatoYmd($this->factura['fechaEmision']);
        error_log('carpetaYmd'.$carpetaYmd);
        return $this->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'))
                    ->view('emails.notification')
                    ->subject('Notification')
                    ->with($data)
                    ->attach('C:\\impuestos\\colquechaca\\'.$carpetaYmd.'\\'.$this->factura['nroFactura'].'.xml', [
                        'as' => $this->factura['nroFactura'].'.xml',
                        'mime' => 'application/xml',
                    ])
                    ->attach('C:\\impuestos\\colquechaca\\'.$carpetaYmd.'\\'.$this->factura['nroFactura'].'.pdf', [
                        'as' => $this->factura['nroFactura'].'.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
    private function formatoYmd($fecha){
        $fecha = substr($fecha, 0, 10); // 2021-08-31T00:00:00-04:00 (10 caracteres
        $fecha = explode('-', $fecha);
        return $fecha[0].$fecha[1].$fecha[2];
    }
}
