<?php

namespace App\Mail;

use App\Http\Controllers\PDF\GeneratePDF;
use App\Patrones\DocumentoSector;
use App\Patrones\Env;
use App\Patrones\TipoPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsumoBasicoMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $codigoDocumentoSector = DocumentoSector::ServiciosBasicos;
    private $factura = null;
    private $ciclo = '';
    private $periodo = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($factura, $ciclo, $periodo)
    {
        $this->factura = $factura;
        $this->ciclo = $ciclo;
        $this->periodo = $periodo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $cuenta = "{$this->factura->pla_matr}{$this->factura->pla_dive}";

        $factura = [
            "numero_factura" => $this->factura->pla_nume,
            "monto" => $this->factura->pla_totmes,
            "fecha" => $this->factura->pla_femi,
            "periodo" => $this->periodo,
            "nombre" => $this->factura->nombre
        ];
        GeneratePDF::generate(Env::carpetaBackups . "$this->ciclo/{$cuenta}_{$this->periodo}.xml", TipoPdf::pdfServicioBasico);

        $files = [
            Env::carpetaBackups . "$this->ciclo/{$cuenta}_{$this->periodo}.xml",
            Env::carpetaBackups . "$this->ciclo/{$cuenta}_{$this->periodo}.pdf"
        ];

        return $this->view('emails.notificacion')
            ->subject("Factura periodo: {$this->periodo}")
            ->with(["factura" => $factura, "codigoDocumentoSector" => $this->codigoDocumentoSector])
            ->attach($files[0])
            ->attach($files[1]);
    }
}
