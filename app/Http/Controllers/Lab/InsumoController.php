<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Lab\Insumo;
use App\Models\Lab\InventarioInsumo;
use Illuminate\Http\Request;
use Flash;

class InsumoController extends AppBaseController
{
    public function index(Request $request)
    {
        $insumos = Insumo::
        orderByDesc('nombre')->paginate(50);
        return view('lab.insumos.index')
            ->with('insumos', $insumos);
    }


    public function store(Request $request)
    {
        $input = $request->all();

        Insumo::create($input);

        Flash::success('Insumo guardado correctamente.');
        return redirect(route('insumos.index'));
    }


    public function actualizar(Request $request)
    {
        $id = $request->id;
        $insumo = Insumo::find($id);

        if (empty($insumo)) {
            Flash::error('Insumo no encontrado.');

            return redirect(route('insumos.index'));
        }

        $input = $request->all();
        $insumo->update($input);
        Flash::success('Insumo guardado correctamente.');
        return redirect(route('insumos.index'));
    }


    public function actualizarInventario(Request $request)
    {
        $insumo = Insumo::find($request->insumo_id);
        $input = $request->all();

        if (empty($insumo)) {
            Flash::error('Insumo no encontrado.');
            return redirect(route('insumos.index'));
        }

        if ($request->tipo == 'Egreso') {
            $input["cantidad"] = abs($insumo->stock - $request->cantidad);
            if($insumo->stock <= $request->cantidad){
                Flash::error('Error en la cantidad ingresada');
                return redirect(route('insumos.index'));
            }
        }
        InventarioInsumo::create($input);

        Flash::success('Movimiento guardado correctamente.');
        return redirect(route('insumos.index'));
    }

    public function show($id)
    {
        $insumos = InventarioInsumo::whereInsumoId($id)->orderBy('fecha')->paginate(50);
        if ($insumos->count() == 0) {
            Flash::error('Insumo no encontrado.');
            return redirect(route('insumos.index'));
        }
        return view('lab.insumos.inventario')
            ->with('insumos', $insumos);
    }

    public function destroy($id)
    {
        $insumo = InventarioInsumo::find($id);

        if (empty($insumo)) {
            Flash::error('Movimiento no encontrado');

            return redirect(route('insumos.show', ['insumo' => $insumo->insumo_id]));
        }

        InventarioInsumo::destroy($id);

        Flash::success('Movimiento eliminado correctamente.');

        return redirect(route('insumos.show', ['insumo' => $insumo->insumo_id]));
    }

    public function getInsumosPdf()
    {
        $insumos = Insumo::orderByDesc('nombre')
            ->get();
        $vistaurl = "lab.insumos.reporte_pdf";
        $view = \View::make($vistaurl, compact('insumos'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('stockInsumos.pdf');
    }
}
