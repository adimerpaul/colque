<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Impuestos\ServicioSincronizacionController;
use Illuminate\Http\Request;

class SincronizacionController extends Controller
{
    public function sincronizar(Request $request)
    {
        $codigoPuntoVenta = $request['codigoPuntoVenta'];

        $servicioSincronizacion = new ServicioSincronizacionController($codigoPuntoVenta);

//        $servicioSincronizacion->sincronizarFechaHora();
        $servicioSincronizacion->sincronizarActividades();
        $servicioSincronizacion->sincronizarListaActividadesDocumentoSector();
//        $servicioSincronizacion->sincronizarListaLeyendasFactura();
//        $servicioSincronizacion->sincronizarListaMensajesServicios();
//        $servicioSincronizacion->sincronizarListaProductosServicios();
//        $servicioSincronizacion->sincronizarParametricaEventosSignificativos();
//        $servicioSincronizacion->sincronizarParametricaMotivoAnulacion();
//        $servicioSincronizacion->sincronizarParametricaPaisOrigen();
//        $servicioSincronizacion->sincronizarParametricaTipoDocumentoIdentidad();
//        $servicioSincronizacion->sincronizarParametricaTipoDocumentoSector();
//        $servicioSincronizacion->sincronizarParametricaTipoEmision();
//        $servicioSincronizacion->sincronizarParametricaTipoHabitacion();
//        $servicioSincronizacion->sincronizarParametricaTipoMetodoPago();
        //$servicioSincronizacion->sincronizarParametricaTipoMoneda();
//        $servicioSincronizacion->sincronizarParametricaTipoPuntoVenta();
//        $servicioSincronizacion->sincronizarParametricaTiposFactura();
//        $servicioSincronizacion->sincronizarParametricaUnidadMedida();

        return $this->successMessage("Se sincronizo correctamente los parametros");
    }

    public function listarParametros(){
        $archivos = [];
        $folder = "./results_sincronizacion/";
        if ($handler = opendir($folder)) {
            while (false !== ($archivo = readdir($handler))) {
                if($archivo != '.' && $archivo != '..')
                    $archivos[] = ["archivo" => $archivo];
            }
            closedir($handler);
            return response()->json(["success" => true, "archivos" => $archivos]);
        }
        else
            return $this->errorMessage("Realize la sincronización");
    }

    public function getMotivosAnulacion(){
        $file = fopen("./results_sincronizacion/sincronizarParametricaMotivoAnulacion.csv", "r");

        $motivos = [];
        while($data = fgetcsv($file, 1000, ";")){
            if($data[0] != 0)
                $motivos[] = ["id" => $data[0], "text" => $data[1]];
        }
        fclose($file);

        return response()->json($motivos);
    }
}
