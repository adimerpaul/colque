<?php

namespace App\Http\Controllers\Impuestos;

use App\Patrones\Env;

class ServicioCodigoController extends ControllerSoap
{
    use CodigoImpuestos;
    private $wsdl = Env::url . "FacturacionCodigos?WSDL";
    private $codigoPuntoVenta;

    public function __construct($codigoPuntoVenta)
    {
        $this->codigoPuntoVenta = $codigoPuntoVenta;
    }

    public function cuis()
    {
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudCuis" => [
                "codigoAmbiente" => Env::codigoAmbiente,
                "codigoModalidad" => Env::codigoModalidad,
                "codigoPuntoVenta" => $this->codigoPuntoVenta,
                "codigoSistema" => Env::codigoSistema,
                "codigoSucursal" => Env::codigoSucursal,
                "nit" => Env::nit,
            ]
        );

        $result = $client->cuis($params);
        return $result->RespuestaCuis;
    }

    public function cufd()
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudCufd" => [
                "codigoAmbiente" => Env::codigoAmbiente,
                "codigoModalidad" => Env::codigoModalidad,
                "codigoPuntoVenta" => $this->codigoPuntoVenta,
                "codigoSistema" => Env::codigoSistema,
                "codigoSucursal" => Env::codigoSucursal,
                "cuis" => $this->getCui(),
                "nit" => Env::nit,
            ]
        );

        $result = $client->cufd($params);
        return $result->RespuestaCufd;
    }

    public function verificarNit($codigoPuntoVenta, $nitParaVerificacion)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudVerificarNit" => [
                "codigoAmbiente" => Env::codigoAmbiente,
                "codigoModalidad" => Env::codigoModalidad,
                "codigoSistema" => Env::codigoSistema,
                "codigoSucursal" => Env::codigoSucursal,
                "cuis" => $this->getCui(),
                "nit" => Env::nit,
                "nitParaVerificacion" => $nitParaVerificacion,
            ]
        );

        if (!is_null($client)) {
            $result = $client->verificarNit($params);
            $data = $result->RespuestaVerificarNit->mensajesList;
            return $data->codigo == 986;
        } else {
            exit;
        }
    }
}
