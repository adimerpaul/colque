<?php

namespace App\Http\Controllers\Impuestos;

use App\Http\Controllers\EnvioFacturaController;
use App\Patrones\CodigoEmision;
use App\Patrones\Env;
use App\Patrones\TipoFactura;
use mysql_xdevapi\Exception;

class ServicioFacturacionController extends ControllerSoap
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
            "tipoFacturaDocumento" => TipoFactura::FacturaConDerechoACreditoFiscal,
        ];
    }
    private function getParamsLibreConsignacion($codigoEmision)
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
            "tipoFacturaDocumento" => TipoFactura::FacturaSinDerechoACreditoFiscal,
        ];
    }
    private function getParamsExportacionMineral($codigoEmision)
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
            "tipoFacturaDocumento" => TipoFactura::FacturaSinDerechoACreditoFiscal,
        ];
    }
    private function getParamsCompraVenta($codigoEmision)
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
            "tipoFacturaDocumento" => TipoFactura::FacturaConDerechoACreditoFiscal,
        ];
    }

    private function getParamsAnulacion($codigoEmision)
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
            "tipoFacturaDocumento" => TipoFactura::FacturaConDerechoACreditoFiscal,
        ];
    }

    private function getFileGzip($fileName, $carpeta)
    {
        $fileName = storage_path($this->carpetaBase . "/" . $carpeta . "/" . $fileName);

        $handle = fopen($fileName, "rb");
        $contents = fread($handle, filesize($fileName));
        fclose($handle);

        return $contents;
    }


    private function getFileGzipIndividual($fileName)
    {
        $handle = fopen($fileName, "rb");
        $contents = fread($handle, filesize($fileName));
        fclose($handle);

        return $contents;
    }

    public function recepcionFactura($fileName)
    {
       $archivo = $this->getFileGzipIndividual($fileName);
        $hash256 = hash('sha256', $archivo);
        $fechaEnvio = $this->servicioSincrionizacion->sincronizarFechaHora();

        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudServicioRecepcionFactura" => $this->getParams(CodigoEmision::EnLinea) + [
                    "archivo" => $archivo,
                    "fechaEnvio" => $fechaEnvio,
                    "hashArchivo" => $hash256,
                ]
        );

        $result = $client->recepcionFactura($params);
        return $result->RespuestaServicioFacturacion;
    }
    public function recepcionEnLineaCompraVenta($request)
    {
        $fileName = $request['fileName'];
        try {
        $archivo = $this->getFileGzipIndividual($fileName);
        $hash256 = hash('sha256', $archivo);

        $fechaEnvio = $request['fechaEmision'];
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudServicioRecepcionFactura" => $this->getParamsCompraVenta(CodigoEmision::EnLinea) + [
                    "archivo" => $archivo,
                    "fechaEnvio" => $fechaEnvio,
                    "hashArchivo" => $hash256,
                ]
        );


            $result = $client->recepcionFactura($params);
            return $result;


        }
        catch (\Exception $e){
            return false;
        }


    }

    public function recepcionEnLineaLibreConsignacion($request)
    {
        $fileName = $request['fileName'];

        $archivo = $this->getFileGzipIndividual($fileName);
        $hash256 = hash('sha256', $archivo);

        $fechaEnvio = $request['fechaEmision'];
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudServicioRecepcionFactura" => $this->getParamsLibreConsignacion(CodigoEmision::EnLinea) + [
                    "archivo" => $archivo,
                    "fechaEnvio" => $fechaEnvio,
                    "hashArchivo" => $hash256,
                ]
        );

        $result = $client->recepcionFactura($params);
        return $result;
    }

    public function recepcionEnLineaExportacionMineral($request)
    {
        $fileName = $request['fileName'];

        try {

            $archivo = $this->getFileGzipIndividual($fileName);
            $hash256 = hash('sha256', $archivo);

            $fechaEnvio = $request['fechaEmision'];
            $client = $this->getClient($this->wsdl);
            $params = array(
                "SolicitudServicioRecepcionFactura" => $this->getParamsExportacionMineral(CodigoEmision::EnLinea) + [
                        "archivo" => $archivo,
                        "fechaEnvio" => $fechaEnvio,
                        "hashArchivo" => $hash256,
                    ]
            );

            $result = $client->recepcionFactura($params);
            return $result;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function recepcionEnLineaLey($request)
    {
        $fileName = $request['fileName'];

        $archivo = $this->getFileGzipIndividual($fileName);
        $hash256 = hash('sha256', $archivo);

        $fechaEnvio = $request['fechaEmision'];
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudServicioRecepcionFactura" => $this->getParams(CodigoEmision::EnLinea) + [
                    "archivo" => $archivo,
                    "fechaEnvio" => $fechaEnvio,
                    "hashArchivo" => $hash256,
                ]
        );

        $result = $client->recepcionFactura($params);
        return $result;
    }
    public function recepcionMasivaFactura($request)
    {
        $client = $this->getClient($this->wsdl);

        $fileName = $request['fileName'];
        $carpeta = $request['carpeta'];
        $cantidad = $request['cantidad'];
        $idEnvio = $request['idEnvio'];

        $archivo = $this->getFileGzip($fileName, $carpeta);
        $hash256 = hash('sha256', $archivo);
        $fechaEnvio = $this->servicioSincrionizacion->sincronizarFechaHora();

        $params = array(
            "SolicitudServicioRecepcionMasiva" => (object)($this->getParams(CodigoEmision::EmisionMasiva) +
                [
                    "archivo" => $archivo,
                    "fechaEnvio" => $fechaEnvio,
                    "hashArchivo" => $hash256,
                    "cantidadFacturas" => $cantidad,
                ])
        );

        $result = $client->recepcionMasivaFactura($params);
        $data = $result->RespuestaServicioFacturacion;

        if ($data->transaccion === true) //se ha enviado correctamente
        {
            EnvioFacturaController::updateTablaEnvios($idEnvio, true, $data->codigoRecepcion);

            return response()->json([
                "success" => true,
                "message" => "Enviado correctamente! Estado: { $data->codigoDescripcion }",
                "detalle" => $data
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Ha ocurrido un error en el envío!",
                "errors" => $data
            ]);
        }
    }

    public function recepcionPaqueteFactura($request)
    {
        $client = $this->getClient($this->wsdl);

        $fileName = $request['fileName'];
        $cantidad = $request['cantidad'];
        $codigoEnvio = $request['codigoEnvio'];

        $archivo = $this->getFileGzipIndividual($fileName);
        $hash256 = hash('sha256', $archivo);
        $fechaEnvio = $this->servicioSincrionizacion->sincronizarFechaHora();

        $params = array(
            "SolicitudServicioRecepcionPaquete" => $this->getParams(CodigoEmision::FueraDeLinea) + [
                    "archivo" => $archivo,
                    "fechaEnvio" => $fechaEnvio,
                    "hashArchivo" => $hash256,
                    "cantidadFacturas" => $cantidad,
                    "codigoEvento" => $codigoEnvio,
                    "cafc" => Env::cafc
                ]
        );

        $result = $client->recepcionPaqueteFactura($params);
        return $result->RespuestaServicioFacturacion;
    }

    public function verificacionEstadoFactura($cuf)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioVerificacionEstadoFactura" => $this->getParams(CodigoEmision::EnLinea) + [
                    "cuf" => $cuf,
                ]
        );

        $result = $client->verificacionEstadoFactura($params);
        return $result->RespuestaServicioFacturacion;
    }

    public function validacionRecepcionMasivaFactura($codigoRecepcion)
    {
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudServicioValidacionRecepcionMasiva" => $this->getParams(CodigoEmision::EmisionMasiva) + [
                    "codigoRecepcion" => $codigoRecepcion
                ]
        );

        $result = $client->validacionRecepcionMasivaFactura($params);
        return $result->RespuestaServicioFacturacion;
    }

    public function validacionRecepcionPaqueteFactura($codigoRecepcion)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioValidacionRecepcionPaquete" => $this->getParams(CodigoEmision::FueraDeLinea) + [
                    "codigoRecepcion" => $codigoRecepcion
                ]
        );

        $result = $client->validacionRecepcionPaqueteFactura($params);
        return $result->RespuestaServicioFacturacion;
    }

    public function anulacionFactura($cuf, $codigoMotivo)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioAnulacionFactura" => $this->getParamsAnulacion(CodigoEmision::EnLinea) + [
                    "codigoMotivo" => $codigoMotivo,
                    "cuf" => $cuf,
                ]
        );

        $result = $client->anulacionFactura($params);
        return $result->RespuestaServicioFacturacion;
    }
    public function anulacionFacturaCompraVenta($cuf, $codigoMotivo)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioAnulacionFactura" => $this->getParamsCompraVenta(CodigoEmision::EnLinea) + [
                    "codigoMotivo" => $codigoMotivo,
                    "cuf" => $cuf,
                ]
        );

        $result = $client->anulacionFactura($params);
        return $result->RespuestaServicioFacturacion;
    }

    public function reversionFacturaCompraVenta($cuf)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioReversionAnulacionFactura" => $this->getParamsCompraVenta(CodigoEmision::EnLinea) + [
                    "cuf" => $cuf,
                ]
        );

        $result = $client->reversionAnulacionFactura($params);
        return $result->RespuestaServicioFacturacion;
    }
    public function anulacionFacturaLibre($cuf, $codigoMotivo)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioAnulacionFactura" => $this->getParamsLibreConsignacion(CodigoEmision::EnLinea) + [
                    "codigoMotivo" => $codigoMotivo,
                    "cuf" => $cuf,
                ]
        );

        $result = $client->anulacionFactura($params);
        return $result->RespuestaServicioFacturacion;
    }
    public function anulacionFacturaExportacion($cuf, $codigoMotivo)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioAnulacionFactura" => $this->getParamsExportacionMineral(CodigoEmision::EnLinea) + [
                    "codigoMotivo" => $codigoMotivo,
                    "cuf" => $cuf,
                ]
        );

        $result = $client->anulacionFactura($params);
        return $result->RespuestaServicioFacturacion;
    }


    public function revertirFacturaExportacion($cuf)
    {
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioReversionAnulacionFactura" => $this->getParamsExportacionMineral(CodigoEmision::EnLinea) + [
                    "cuf" => $cuf,
                ]
        );

        $result = $client->reversionAnulacionFactura($params);
        return $result->RespuestaServicioFacturacion;
    }
}
