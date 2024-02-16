<?php

namespace App\Http\Controllers;

use App\Models\CampoReporte;
use App\Models\FormularioLiquidacion;
use App\Models\Producto;
use App\Patrones\Estado;
use Illuminate\Http\Request;

class LoteController
{
    public function index(Request $request)
    {
        $fechaInicio=$request->fecha_inicio;
        $fechaFin=$request->fecha_fin;
        $fechaFinal=$fechaFin;
        $productoLetra = $request->producto_id;
        $estado=$request->txtEstado;

        if(is_null($productoLetra)){
            $producto = Producto::whereLetra('B')->first();
        }
        else
            $producto = Producto::whereLetra($productoLetra)->first();
        if($fechaInicio == null OR $fechaFin == null)
        {
            $formularios = FormularioLiquidacion::where('id', '>', '300000000')->paginate(100);
        }
        else{

            $txtBuscar = $request->txtBuscar;
            if (is_null($txtBuscar))
                $txtBuscar = '';
            $fechaFin=date('Y-m-d', strtotime($fechaFin. ' + 1 days'));
            $formularios = FormularioLiquidacion::
            where([['fecha_liquidacion', '>=', $fechaInicio],['fecha_liquidacion', '<', $fechaFin]])
                ->where('producto', 'like', "$productoLetra%")
                ->where(function ($q) use ($estado) {
                    if (!is_null($estado)) {
                        if ($estado == '%') {
                            $q->whereIn('estado', [Estado::Liquidado, Estado::EnProceso]);
                        } else {
                            $q->where('estado', $estado);
                        }

                    }
                })
                ->where(function ($q) use ($txtBuscar) {
                    $q->where('producto', 'ilike', "%{$txtBuscar}%")
                        ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)",
                            ["%{$txtBuscar}%"]);

                })
                ->orderBy('numero_lote')
                ->paginate(100);
        }

        return view('lotes.index', compact('formularios', 'fechaInicio', 'fechaFinal', 'producto'));
    }

}
