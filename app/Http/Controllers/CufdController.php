<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Impuestos\ServicioCodigoController;
use App\Models\Cufd;
use App\Patrones\Fachada;
use Illuminate\Http\Request;

class CufdController extends Controller
{
    public function registerCufd(Request $request)
    {
        $codigoPuntoVenta = $request['codigoPuntoVenta'];

        if ($this->isCufdRegister($codigoPuntoVenta))
            return $this->errorMessage("El CUFD ya se encuentra registrado para el punto de venta: $codigoPuntoVenta");

        $data = (new ServicioCodigoController($codigoPuntoVenta))->cufd();

        $fecha = date("Y-m-d H:i:s", strtotime("$data->fechaVigencia"));
        $fecha1 = strtotime('-8 hour', strtotime($fecha));
        $fechaVigencia = date("Y-m-d H:i:s", $fecha1);

        $lol = Cufd::create([
            'transaccion' => $data->transaccion,
            'codigo_punto_venta' => $codigoPuntoVenta,
            'codigo' => $data->codigo,
            'codigo_control' => $data->codigoControl,
            'direccion' => $data->direccion,
            'fecha_vigencia' => $fechaVigencia
        ]);

        //Cufd::destroy($lol->id);
        return $this->successMessage("Se registro el CUFD correctamente, para el punto de venta: $codigoPuntoVenta");
    }

    private function isCufdRegister($codigoPuntoVenta)
    {
        $actualDate = Fachada::getFecha()->format("Y-m-d H:i:s");
        $cufd = Cufd::where('fecha_vigencia', '>=', $actualDate)
            ->whereCodigoPuntoVenta($codigoPuntoVenta)
            ->orderByDesc('id')
            ->first();
        return !is_null($cufd);
    }
}
