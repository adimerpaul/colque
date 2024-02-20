<?php

namespace App\Http\Controllers\Impuestos;

use App\Patrones\Env;

class ServicioOperacionController extends ControllerSoap
{
    use CodigoImpuestos;

    private $wsdl = Env::url . "FacturacionOperaciones?WSDL";
    private $servicioSincronizacion;
    private $codigoDocumentoSector;
    private $codigoPuntoVenta;

    public function __construct($codigoDocumentoSector, $codigoPuntoVenta)
    {
        $this->codigoDocumentoSector = $codigoDocumentoSector;
        $this->codigoPuntoVenta = $codigoPuntoVenta;
        $this->servicioSincronizacion = new ServicioSincronizacionController($codigoPuntoVenta);
    }

    public function consultaEventoSignificativo()
    {
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudConsultaEvento" => [
                "codigoAmbiente" => Env::codigoAmbiente,
                "codigoPuntoVenta" => $this->codigoPuntoVenta,
                "codigoSistema" => Env::codigoSistema,
                "codigoSucursal" => Env::codigoSucursal,
                "cufd" => $this->getCufd()->codigo,
                "cuis" => $this->getCui(),
                "fechaEvento" => $this->servicioSincronizacion->sincronizarFechaHora(),
                "nit" => Env::nit,
            ]
        );
        $result = $client->consultaEventoSignificativo($params);
        return $result->RespuestaListaEventos;
    }

    public function registroEventoSignificativo($codigoMotivoEvento, $descripcion, $cufdEvento, $fechaHoraInicioEvento)
    {
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudEventoSignificativo" => [
                "codigoAmbiente" => Env::codigoAmbiente,
                "codigoMotivoEvento" => $codigoMotivoEvento,
                "codigoPuntoVenta" => $this->codigoPuntoVenta,
                "codigoSistema" => Env::codigoSistema,
                "codigoSucursal" => Env::codigoSucursal,
                "cufd" => $this->getCufd()->codigo, //ultimo4
                "cufdEvento" => $cufdEvento,  //penultimo
                "cuis" => $this->getCui(),
                "descripcion" => $descripcion,
                "fechaHoraFinEvento" => $this->servicioSincronizacion->sincronizarFechaHora(),
                "fechaHoraInicioEvento" => $fechaHoraInicioEvento,
                "nit" => Env::nit,
            ]
        );

        $result = $client->registroEventoSignificativo($params);
        return $result->RespuestaListaEventos;
    }

    public function registroPuntoVenta()
    {
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudRegistroPuntoVenta" => [
                "codigoAmbiente" => Env::codigoAmbiente,
                "codigoModalidad" => Env::codigoModalidad,
                "codigoSistema" => Env::codigoSistema,
                "codigoSucursal" => Env::codigoSucursal,
                "codigoTipoPuntoVenta" => 5,
                "cuis" => $this->getCui(),
                "descripcion" => "Punto de venta 2",
                "nit" => Env::nit,
                "nombrePuntoVenta" => "Punto de venta en el banco 2",
            ]
        );;

        $result = $client->registroPuntoVenta($params);
        return $result->RespuestaRegistroPuntoVenta;
    }

    public function consultaPuntoVenta()
    {
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudConsultaPuntoVenta" => [
                "codigoAmbiente" => Env::codigoAmbiente,
                "codigoSistema" => Env::codigoSistema,
                "codigoSucursal" => Env::codigoSucursal,
                "cuis" => $this->getCui(),
                "nit" => Env::nit,
            ]
        );;

        $result = $client->consultaPuntoVenta($params);
        return $result->RespuestaConsultaPuntoVenta;
    }
}
