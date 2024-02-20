<?php

namespace App\Mail;

use App\Patrones\Env;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class DocumentoAjusteMailable extends Mailable
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
        $carpeta = $this->factura->tipo === 'Conciliacion' ? "NotaConciliacion" : "NotaDebitoCredito";

        $fecha = date("Ymd", strtotime($this->factura->fecha));
        $nroFactura = (int)$this->factura->numero;

        $files = [
            Env::carpetaBackups . "$carpeta/$fecha/$nroFactura.xml",
            Env::carpetaBackups . "$carpeta/$fecha/$nroFactura.pdf"
        ];

        if (File::isFile($files[0]) && File::isFile($files[0])) {
            return $this->view('emails.documento_ajuste_notificacion')
                ->subject("Documento de Ajuste")
                ->with(["factura" => $this->factura])
                ->attach($files[0])
                ->attach($files[1]);
        }
        else{
            throw new \Exception("El archivo XML o PDF no existe o esta dañado", 926);
        }
    }
}
