<?php

namespace App\Mail;

use App\Http\Controllers\PDF\GeneratePDF;
use App\Patrones\DocumentoSector;
use App\Patrones\Env;
use App\Patrones\TipoPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtroIngresoMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $codigoDocumentoSector = DocumentoSector::CompraVenta;
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
        $factura = [
            "numero_factura" => $this->factura->pla_nume,
            "monto" => $this->factura->pla_totmes,
            "fecha" => $this->factura->pla_femi,
            "nombre" => $this->factura->nombre
        ];

        $fecha = date("Ymd", strtotime($this->factura->pla_femi));
        $nroFactura = (int)$this->factura->pla_nume;

        GeneratePDF::generate(Env::carpetaBackups . "$fecha/$nroFactura-signed.xml", TipoPdf::pdfOtrosIngresos);

        $files = [
            Env::carpetaBackups . "$fecha/$nroFactura-signed.xml",
            Env::carpetaBackups . "$fecha/$nroFactura-signed.pdf"
        ];

        return $this->view('emails.notificacion')
            ->subject("Factura de servicio pagado")
            ->with(["factura" => $factura, "codigoDocumentoSector" => $this->codigoDocumentoSector])
            ->attach($files[0])
            ->attach($files[1]);
    }
}
