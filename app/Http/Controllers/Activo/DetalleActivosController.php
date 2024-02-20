<?php

namespace App\Http\Controllers\Activo;

use App\Http\Controllers\Controller;
use App\Models\Activo\DetalleActivo;
use App\Models\Activo\ActivoFijo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Flash;
use function redirect;
use function view;

class DetalleActivosController extends Controller
{
    
    public function nuevaFactura($id)
    {
        $activoFijo=ActivoFijo::find($id);
        $facturas=DetalleActivo::whereActivoFijoId($id)->get();
        return view('activos.activos_fijos.factura',compact('activoFijo', 'facturas'));
    }

    
    public function store(Request $request)
    {
        $input = $request->all();
        $activoFijo = ActivoFijo::find($request->activo_fijo_id);
        DetalleActivo::create($input);
        Flash::success('Cantidad añadida con exito del codigo: ' . $activoFijo->codigo);
        return redirect(route('nueva-factura', ['id' => $activoFijo->id]));
        
    }

    
    public function show(DetalleActivo $facturaActivos)
    {
        //
    }

   
    public function edit(DetalleActivo $facturaActivos)
    {
        //
    }

    
    public function update(Request $request, DetalleActivo $facturaActivos)
    {
        //
    }

   
    public function destroy($id)
    {
        $factura=DetalleActivo::find($id);
        $factura->delete();
        Flash::error('Datos eliminados correctamente');
        return redirect()
            ->route('factura.nuevaFactura', ['id' => $activoFijoBaja->activo_fijo_id]);
    }
}
