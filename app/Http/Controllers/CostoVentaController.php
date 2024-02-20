<?php

namespace App\Http\Controllers;

use App\Models\Costo;
use App\Models\CostoVenta;
use App\Models\Venta;
use App\Patrones\EstadoVenta;
use Illuminate\Http\Request;
use Flash;
use Response;

class CostoVentaController extends AppBaseController
{

    public function index(Request $request)
    {
        $venta_id = $request->venta_id;

        $costos = CostoVenta::whereVentaId($venta_id)
            ->orderBy('descripcion')->get();
        return $costos;
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $venta=Venta::find($request->venta_id);

        $contador = CostoVenta::whereVentaId($request->venta_id)->whereDescripcion($request->descripcion)->where('descripcion', '!=', 'Otros')->count();

        if ($contador>0) {
            return response()->json(['res' => false, 'message' => 'La descripción del costo ya fue agregada anteriormente']);
        }

        if ($venta->estado !== EstadoVenta::EnProceso) {
            return response()->json(['res' => false, 'message' => 'La venta ya fue liquidada anteriormente']);
        }

        if ($request->descripcion =='Otros') {
            $input["descripcion"]= $request->descripcion.': ' .$request->otros;
        }
        $costo = CostoVenta::create($input);

        return response()->json(['res' => true, 'message' => 'Costo registrado correctamente']);
    }

    public function destroy($id)
    {
        $costo = CostoVenta::find($id);
        $venta=Venta::find($costo->venta_id);
        if ($venta->estado !== EstadoVenta::EnProceso) {
            return response()->json(['res' => false, 'message' => 'La venta ya fue liquidada anteriormente']);
        }
        if (empty($costo)) {
            return response()->json(['res' => false, 'message' => 'Costo no encontrado']);
        }
        $costo->delete($id);
        return response()->json(['res' => true, 'message' => 'Costo eliminado correctamente']);
    }

    public function registrarLaboratorio($id, $monto)
    {

        $input['monto'] = $monto;
        $input['venta_id'] = $id;
        $input['descripcion'] = 'Servicio de análisis químico';
        CostoVenta::create($input);

        return response()->json(['res' => true, 'message' => 'Monto guardado correctamente.']);
    }

    public function agregarCosto($ventaId, $monto, $descripcion){
        $input['monto'] = $monto;
        $input['venta_id'] = $ventaId;
        $input['descripcion'] = $descripcion;
        CostoVenta::create($input);

        return response()->json(['res' => true, 'message' => 'Monto guardado correctamente.']);
    }
}
