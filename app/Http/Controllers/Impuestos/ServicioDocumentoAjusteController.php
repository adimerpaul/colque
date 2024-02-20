<?php

namespace App\Http\Controllers\Impuestos;

use App\Patrones\CodigoEmision;
use App\Patrones\Env;
use App\Patrones\TipoFactura;

class ServicioDocumentoAjusteController extends ControllerSoap
{
    use CodigoImpuestos;

    private $wsdl = "";
    private $carpetaBase;
    private $servicioSincrionizacion;
    private $codigoDocumentoSector;
    private $codigoPuntoVenta;

    public function __construct($codigoDocumentoSector, $codigoPuntoVenta)
    {
        $this->codigoDocumentoSector = $codigoDocumentoSector;
        $this->codigoPuntoVenta = $codigoPuntoVenta;

        list($this->wsdl, $this->carpetaBase) = UrlServicioFacturacion::getUrlServicio($codigoDocumentoSector);

        $this->servicioSincrionizacion = new ServicioSincronizacionController($codigoPuntoVenta);
    }

    private function getParams($codigoEmision)
    {
        return [
            "codigoAmbiente" => Env::codigoAmbiente,
            "codigoDocumentoSector" => $this->codigoDocumentoSector,
            "codigoEmision" => $codigoEmision,
            "codigoModalidad" => Env::codigoModalidad,
            "codigoPuntoVenta" => $this->codigoPuntoVenta,
            "codigoSistema" => Env::codigoSistema,
            "codigoSucursal" => Env::codigoSucursal,
            "cufd" => $this->getCufd()->codigo,
            "cuis" => $this->getCui(),
            "nit" => Env::nit,
            "tipoFacturaDocumento" => TipoFactura::DocumentoDeAjuste,
        ];
    }

    private function getFileGzip($fileName)
    {
        $handle = fopen($fileName, "rb");
        $contents = fread($handle, filesize($fileName));
        fclose($handle);

        return $contents;
    }


    public function recepcionDocumentoAjuste($request)
    {
        $fileName = $request['fileName'];

        $archivo = $this->getFileGzip($fileName);
        $hash256 = hash('sha256', $archivo);
        $fechaEnvio = $this->servicioSincrionizacion->sincronizarFechaHora();
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudServicioRecepcionDocumentoAjuste" => $this->getParams(CodigoEmision::EnLinea) + [
                    "archivo" => $archivo,
                    "fechaEnvio" => $fechaEnvio,
                    "hashArchivo" => $hash256,
                ]
        );

        $result = $client->recepcionDocumentoAjuste($params);
        return $result->RespuestaServicioFacturacion;
    }

    public function verificacionEstadoDocumentoAjuste($cuf)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioVerificacionEstadoDocumentoAjuste" => $this->getParams(CodigoEmision::EnLinea) + [
                    "cuf" => $cuf,
                ]
        );

        $result = $client->verificacionEstadoDocumentoAjuste($params);
        return $result->RespuestaServicioFacturacion;
    }

    public function anulacionDocumentoAjuste($cuf, $codigoMotivo)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioAnulacionDocumentoAjuste" => $this->getParams(CodigoEmision::EnLinea) + [
                    "codigoMotivo" => $codigoMotivo,
                    "cuf" => $cuf,
                ]
        );

        $result = $client->anulacionDocumentoAjuste($params);
        return $result->RespuestaServicioFacturacion;
    }
}
