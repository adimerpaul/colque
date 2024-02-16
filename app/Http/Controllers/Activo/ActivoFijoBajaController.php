<?php

namespace App\Http\Controllers\Activo;

use App\Http\Controllers\Controller;
use App\Models\Activo\ActivoFijo;
use App\Models\Activo\ActivoFijoBaja;
use App\Models\Activo\DetalleActivo;
use Flash;
use Illuminate\Http\Request;
use function redirect;
use function view;

class ActivoFijoBajaController extends Controller
{


    public function nuevaBaja($id)
    {

        $activoFijo=ActivoFijo::find($id);
        //$bajas=ActivoFijoBaja::whereDetalleActivoId(whereActivoFijoId($id)->get();
        $bajas = ActivoFijoBaja::
                whereHas('detalle', function ($q) use ($id){
                    $q->where('activo_fijo_id', $id);
                })
                ->get();

        return view('activos.activos_fijos.baja',compact('activoFijo', 'bajas'));


    }

    public function store(Request $request)
    {

        $input = $request->all();
        $activoFijo = DetalleActivo::find($request->detalle_activo_id);
        $cantidadStock = $activoFijo->cantidad_stock;

        if ($cantidadStock >= $request->cantidad) {
            ActivoFijoBaja::create($input);
            Flash::success('Baja realizada del activo: ' . $activoFijo->codigo);
            return redirect(route('activos-fijos.index'));
        }

        Flash::error('Cantidad mayor a la cantidad actual');
        return redirect()
            ->route('baja-activo', ['id' => $activoFijo->activo_fijo_id]);
    }


    public function destroy($id)
    {
        $activoFijoBaja=ActivoFijoBaja::find($id);
        $activoFijoBaja->delete();
        Flash::error('Baja eliminada correctamente');
        return redirect()
            ->route('baja-activo', ['id' => $activoFijoBaja->detalle->activo_fijo_id]);
    }
}
