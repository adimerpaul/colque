<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Impuestos\ServicioFacturacionController;
use App\Http\Controllers\Impuestos\UrlServicioFacturacion;
use App\Http\Controllers\XmlSource\Files;
use App\Models\Anulado;
use App\Models\EnvioFactura;
use App\Patrones\DocumentoSector;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnvioFacturaController extends Controller
{
    protected ClienteDbfController $dbf;
    public function __construct(ClienteDbfController $dbf)
    {
        $this->dbf = $dbf;
    }

    public function getPaqueteFacturas(Request $request)
    {
        $periodo = $request['tarifa'];
        $ciclo = $request['ciclo'];

        $paquetesFacturas = EnvioFactura::whereCiclo($ciclo)
            ->wherePeriodo($periodo)
            ->orderByDesc('id')
            ->get();

        return response()->json(['success' => true, "paquetesFacturas" => $paquetesFacturas]);
    }

    public function getPaqueteOtrosIngresos(Request $request)
    {
        $gestion = $request['gestion'];

        $paquetesOtrosIngresos = EnvioFactura::whereNull('periodo')
            ->whereNull('ciclo')
            ->whereYear('fecha_generado', $gestion)
            ->orderByDesc('id')
            ->get();

        return response()->json(['success' => true, "paquetesFacturas" => $paquetesOtrosIngresos]);
    }

    public function getFactura(Request $request)
    {
        $ciclo = $request['ciclo'];
        $periodo = $request['tarifa'];
        $cuenta = $request['cuenta'];

        $data = $this->dbf->getGenerados($ciclo, $periodo, $cuenta);

        $anuladoCount = Anulado::where([['periodo', $periodo], ['cuenta', $cuenta]])->orderByDesc('id')->count();

        if($anuladoCount > 0){
            $anulado = Anulado::where([['periodo', $periodo], ['cuenta', $cuenta]])->orderByDesc('id')->first();
            $fechaHoraAnulado = date("Y-m-d H:i:s", strtotime($anulado->created_at));
            $data['cabecera']['fecha_emision'] = $fechaHoraAnulado;
        }

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function getOtroIngreso(Request $request)
    {
        $fecha = $request['fecha'];
        $data = $this->dbf->getOtroIngreso($fecha);
        return response()->json(["success" => true, "data" => $data]);
    }

    public function getOtroIngresoFactura(Request $request)
    {
        $fecha = $request['fecha'];
        $numeroFactura = $request['numeroFactura'];

        $data = $this->dbf->getOtroIngresoFactura($fecha, $numeroFactura);

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function eliminarRecepcionRar(Request $request)
    {
        set_time_limit(0);
        $fileName = $request['fileName'];
        $carpeta = $request['carpeta'];
        $idEnvio = $request['idEnvio'];
        $periodo = $request['periodo'];
        $fechaRecepcion = $request['fechaRecepcion'];
        $codigoDocumentoSector = $request['codigoDocumentoSector'];

        DB::beginTransaction();
        list($wsdl, $carpetaBase) = UrlServicioFacturacion::getUrlServicio($codigoDocumentoSector);
        try {
            EnvioFactura::destroy($idEnvio);
            Files::$esValidadoEnImpuestos = false;
            $facturas = Files::getFilesAndPurge($fileName, $carpeta, $carpetaBase);
            $this->dbf->restoreFactura($codigoDocumentoSector, $facturas, $periodo, $fechaRecepcion);

            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "$fileName se ha eliminado y las cuentas asociadas se han revertido correctamente"
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public static function updateTablaEnvios($id, $seEnvio, $codigoRecepcion)
    {
        $factura = EnvioFactura::find($id);
        $factura->se_envio = $seEnvio;
        $factura->codigo_recepcion = $codigoRecepcion;
        $factura->fecha_recepcion = date("Y-m-d H:i:s");
        return $factura->save();
    }

    public function updateValidacion($id, $seValido, $resultado_validacion)
    {
        $factura = EnvioFactura::find($id);
        $factura->se_valido = $seValido;
        $factura->fecha_validacion = date("Y-m-d H:i:s");
        $factura->resultado_validacion = $resultado_validacion;
        return $factura->save();
    }

    public function deleteEnvioFacturas($periodo, $ciclo)
    {
        EnvioFactura::wherePeriodo($periodo)
            ->whereCiclo($ciclo)
            ->whereSeValido(false)
            ->delete();
    }

    public function deleteEnvioFacturasOtrosIngresos($fecha)
    {
        EnvioFactura::whereNull('periodo')
            ->whereDate('fecha_generado', $fecha)
            ->whereSeValido(false)
            ->delete();
    }

    public function insertTableEnvioFacturas($periodo, $ciclo, $archivo, $hash, $cantidad, $codigoPuntoVenta, $cufId)
    {
        EnvioFactura::create([
            'codigo_punto_venta' => $codigoPuntoVenta,
            'fecha_generado' => date("Y-m-d\TH:i:s.v"),
            'periodo' => $periodo,
            'ciclo' => $ciclo,
            'url' => $archivo,
            'sha256' => $hash,
            'cantidad' => $cantidad,
            'se_envio' => false,
            'codigo_recepcion' => null,
            'fecha_recepcion' => null,
            'se_valido' => false,
            'resultado_validacion' => null,
            'fecha_validacion' => null,
            'cufd_id' => $cufId,
        ]);
    }

    public function insertTableEnvioFacturasOtrosIngresos($archivo, $hash, $cantidad, $fecha, int $codigoPuntoVenta, $cuf_id)
    {
        EnvioFactura::create([
            'codigo_punto_venta' => $codigoPuntoVenta,
            'fecha_generado' => $fecha,
            'periodo' => null,
            'ciclo' => null,
            'url' => $archivo,
            'sha256' => $hash,
            'cantidad' => $cantidad,
            'se_envio' => false,
            'codigo_recepcion' => null,
            'fecha_recepcion' => null,
            'se_valido' => false,
            'resultado_validacion' => null,
            'fecha_validacion' => null,
            'cufd_id' => (int)$cuf_id,
        ]);
    }

    public function recepcionMasivaFactura(Request $request)
    {
        $codigoDocumentoSector = $request['codigoDocumentoSector'];
        $codigoPuntoVenta = $request['codigoPuntoVenta'];

        return (new ServicioFacturacionController($codigoDocumentoSector, $codigoPuntoVenta))->recepcionMasivaFactura($request);
    }

    public function verificacionMasivaFactura(Request $request)
    {
        set_time_limit(0);

        $codigoRecepcion = $request['codigoRecepcion'];
        $idEnvio = $request['idEnvio'];
        $fileName = $request['nombreArchivo'];
        $carpeta = $request['carpeta'];
        $periodo = $request['periodo'];
        $fechaGenerado = date("Y-m-d", strtotime($request['fechaGenerado']));
        $codigoPuntoVenta = $request['codigoPuntoVenta'];
        $codigoDocumentoSector = $request['codigoDocumentoSector'];

        list($wsdl, $carpetaBase) = UrlServicioFacturacion::getUrlServicio($codigoDocumentoSector);
        $servicioFacturacionController = new ServicioFacturacionController($codigoDocumentoSector, $codigoPuntoVenta);
        $data = $servicioFacturacionController->validacionRecepcionMasivaFactura($codigoRecepcion);

        if ($data->codigoEstado == 904) //se ha validado pero tiene errores en impuestos nacionales
        {
            $this->updateValidacion($idEnvio, true, json_encode($data->mensajesList));

            Files::$esValidadoEnImpuestos = false;
            $facturas = Files::getFilesAndPurge($fileName, $carpeta, $carpetaBase);
            $this->dbf->restoreFactura($codigoDocumentoSector, $facturas, $periodo, $fechaGenerado);

            return response()->json([
                "success" => true,
                "message" => "Se validó con errores! Estado: $data->codigoDescripcion",
                "errors" => $data->mensajesList
            ]);
        } else {
            $this->updateValidacion($idEnvio, true, null);
            $facturas = Files::getFilesAndPurge($fileName, $carpeta, $carpetaBase);
            $this->dbf->updateFactura($codigoDocumentoSector, $facturas, $periodo, $fechaGenerado);

            $mensajeAnulacion = "";
            if ($codigoDocumentoSector === DocumentoSector::CompraVenta) {
                $resAnulacion = $this->anularFacturasOtrosIngresos($servicioFacturacionController, $fechaGenerado);
                $mensajeAnulacion = $resAnulacion ? "Se realizó la anulación de las facturas anuladas en el sistema SeLAsis" : "Error! No se pudieron anular en Inpuestos Nacionales las facturas anuladas en el sistema SeLAsis, debe anularlos manualmente";
            }
            $data->comprimido= "nombreArchivo";
            return response()->json([
                "success" => true,
                "message" => "Se ha validado correctamente en Impuestos Nacionales!" . "<br/>" . $mensajeAnulacion,
                "data" => $data
            ]);
        }
    }

    public function verificarFactura(Request $request)
    {
        $cuf = $request['cuf'];
        $codigoPuntoVenta = $request['codigoPuntoVenta'];
        $codigoDocumentoSector = $request['codigoDocumentoSector'];

        $facturacion = new ServicioFacturacionController($codigoDocumentoSector, $codigoPuntoVenta);
        $result = $facturacion->verificacionEstadoFactura($cuf);
        return response()->json(["success" => true, "message" => $result]);
    }

    /**
     * @throws Exception
     */
    public function anularFacturaEnDbf($factura, $codigoDocumentoSector)
    {
        if ($codigoDocumentoSector === DocumentoSector::CompraVenta) {
            $result = $this->dbf->restorePagIngre($factura['numero_factura'], $factura['fecha_emision']);
        } else {
            $matricula = substr($factura['codigo_cliente'],0, -1);
            $tarifa = "{$factura['gestion']}{$factura['mes']}";

            $result = $this->dbf->restorePlanilla($matricula, $tarifa);
        }

        if (!$result)
            throw new \Exception("Error al restaurar en los DBFs, vuelva intentarlo", 926);
    }

    public function anulacionFactura(Request $request)
    {
        $cuf = $request['cuf'];


        $codigoPuntoVenta = $request['codigoPuntoVenta'];
        $codigoDocumentoSector = $request['codigoDocumentoSector'];
        $codigoMotivo = $request['codigoMotivo'];
        $factura = $request['factura'];
        error_log(json_encode($factura));

        $result = $this->dbf->getPagado($factura['cabecera']);

        if($result[0] == false)
            return response()->json(["success" => false, "message" => "No puede anular la factura por que ya esta cancelada"]);

//
//        if(isset( $factura['cabecera']['gestion'])){
//            $numeroFactura = $factura['cabecera']['numero_factura'];
//            $periodo = $factura['cabecera']['gestion'].$factura['cabecera']['mes'];
//            $cuenta = $factura['cabecera']['codigo_cliente'];
//            $monto = $factura['cabecera']['monto_total'];
//            $fechaEmision = substr($factura['cabecera']['fecha_emision'], 0, 10);
//        }
//
////
//        $facturacion = new ServicioFacturacionController($codigoDocumentoSector, $codigoPuntoVenta);
//        $result = $facturacion->anulacionFactura($cuf, $codigoMotivo);
//
//        //enviando el correo electronico
//        $seEnvioemail = false;
//        if (($result->codigoEstado == 905 || $result->codigoEstado == 906)) {
////            if (isset($factura['cabecera']['email']))
////                $seEnvioemail = EnvioEmailController::enviarEmailAnulacion($factura['cabecera']);
//
//            $this->anularFacturaEnDbf($factura['cabecera'], $codigoDocumentoSector);
//
//            if(isset( $factura['cabecera']['gestion'])){
//                Anulado::create([
//                    'numero_factura' => $numeroFactura,
//                    'periodo' => $periodo,
//                    'cuenta' => $cuenta,
//                    'monto' => $monto,
//                    'fecha_emision' => $fechaEmision,
//                    'cuf' => $cuf
//                ]);
//            }
//        }
//
//        return response()->json([
//            "success" => true,
////            "message" => $result,
//            "message" => [
//                "codigoEstado" => 905,
//                "codigoDescripcion" => "Anulación exitosa",
//                "mensajesList" => [
//                    "Anulación exitosa"
//                ]
//            ],
//           // "email" => $seEnvioemail ? "Correo enviado al cliente" : "NO se ha enviado correo al cliente, vuelva a intentarlo, o revise el email del cliente"
//        ]);
    }

    private function anularFacturasOtrosIngresos(ServicioFacturacionController $servicioFacturacionController, $fechaGerenado)
    {
        try {
            $resAnulaciones = true;
            $codigoMotivo = 1;
            $facturasAnuladas = $this->dbf->getAnuladosOtroIngreso($fechaGerenado);
            if (isset($facturasAnuladas->success) && !$facturasAnuladas->success)
                return false;

            foreach ($facturasAnuladas as $facturaAnulada) {
                $cuf = $facturaAnulada["cuf"];
                $result = $servicioFacturacionController->anulacionFactura($cuf, $codigoMotivo);
                if ($result->transaccion) {
                    $this->dbf->anularFacturaOtroIngreso($cuf, $fechaGerenado);
                } else {
                    $resAnulaciones = false;
                    break;
                }
            }
            return $resAnulaciones;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
