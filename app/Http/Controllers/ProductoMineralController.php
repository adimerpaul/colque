<?php

namespace App\Http\Controllers;

use App\Models\CotizacionOficial;
use App\Models\FormularioLiquidacion;
use App\Models\Producto;
use App\Models\ProductoMineral;
use Illuminate\Http\Request;

class ProductoMineralController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(ProductoMineral::with(['mineral'])->whereProductoId($request->producto_id)->orderBy('id')->get(), 200);
    }

    public function store(Request $request)
    {
        try {
            $productoMineral = ProductoMineral::create($request->all());
            return redirect(route('productos.show', [$productoMineral->producto]));
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function destroy($id)
    {
        try {
            ProductoMineral::destroy($id);
            return ['res' => true, 'message' => 'Eliminado correctamente'];
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $productoMineral = ProductoMineral::findOrFail($id);
            $productoMineral->es_penalizacion = !$productoMineral->es_penalizacion;
            $productoMineral->save();
            return ['res' => true, 'message' => 'Cambiado correctamente'];
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function updateLey($id, $ley)
    {
        try {
            $productoMineral = ProductoMineral::findOrFail($id);
            $productoMineral->ley_minima = $ley;
            $productoMineral->save();
            return ['res' => true, 'message' => 'Ley mínima actualizada correctamente'];
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function getCotizacionProductoMineral($formulario, $fecha){
        $mineralesIds = FormularioLiquidacion::find($formulario->id)->liquidacioMinerales->pluck('mineral_id');

        $fechaInferior = null;
        $cotizacion = CotizacionOficial::orderByDesc('fecha')->whereEsAprobado(true)->where('fecha', '<=', $fecha)->first();
        if(!is_null($cotizacion))
            $fechaInferior = $cotizacion->fecha;

        $fechaSuperior = null;
        $cotizacion = CotizacionOficial::orderBy('fecha')->whereEsAprobado(true)->where('fecha', '>=', $fecha)->first();
        if(!is_null($cotizacion))
            $fechaSuperior = $cotizacion->fecha;

        if(is_null($fechaInferior) || is_null($fechaSuperior) )
            return response()->json(['res' => false, 'message' => 'No existe cotizacion oficial para la fecha de recepcion del formulario']);

        $materiales = \DB::select("select m.simbolo , m.nombre, co.fecha, co.monto, co.unidad, co.alicuota_exportacion , co.alicuota_interna
                                    from mineral m inner join
                                         cotizacion_oficial co on co.mineral_id = m.id
                                     where m.id in ? and co.fecha = ?", [$mineralesIds, $fechaInferior]);

        return response()->json(['res' => true, 'data' => $materiales], 200);
    }

    public function getIdByLetra($letra){

        return Producto::whereLetra($letra)->select('id')->first();
    }
}
