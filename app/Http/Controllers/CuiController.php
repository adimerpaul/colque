<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Impuestos\ServicioCodigoController;
use App\Models\Cui;
use App\Patrones\Fachada;
use Illuminate\Http\Request;

class CuiController extends Controller
{
    public function registerCuis(Request $request)
    {
        $codigoPuntoVenta = $request['codigoPuntoVenta'];

        if ($this->isCuiRegister($codigoPuntoVenta))
            return $this->errorMessage("El CUI ya se encuentra registrado para el punto de venta: $codigoPuntoVenta");

        $data = (new ServicioCodigoController($codigoPuntoVenta))->cuis();
        Cui::create([
            "transaccion" => $data->transaccion,
            "codigo" => $data->codigo,
            "fecha_vigencia" => $data->fechaVigencia,
            "codigo_punto_venta" => $codigoPuntoVenta
        ]);
        return $this->successMessage("Se registro el CUI correctamente, para el punto de venta: $codigoPuntoVenta");
    }

    private function isCuiRegister($codigoPuntoVenta)
    {
        $actualDate = Fachada::getFechaHora()->format("Y-m-d H:i:s");
        $cui = Cui::where('fecha_vigencia', '>=', $actualDate)
            ->whereCodigoPuntoVenta($codigoPuntoVenta)
            ->orderByDesc('id')
            ->first();
        return !is_null($cui);
    }
}
