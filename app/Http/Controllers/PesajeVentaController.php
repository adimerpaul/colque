<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Events\AccionCompletaVenta;
use App\Http\Requests\CreatePesajeVentaRequest;
use App\Models\PesajeVenta;
use App\Http\Controllers\AppBaseController;
use App\Models\FormularioLiquidacion;
use App\Models\Venta;
use App\Patrones\EstadoVenta;
use Illuminate\Http\Request;
use Flash;
use Response;

class PesajeVentaController extends AppBaseController
{

    public function index(Request $request)
    {
        $ventaId = $request->venta_id;

        $pesajes = PesajeVenta::whereVentaId($ventaId)->with('chofer', 'vehiculo')
            ->orderBy('id')->get();
        $sumaBruto = $pesajes->sum('peso_bruto_humedo');
        $sumaNeto = $pesajes->sum('peso_neto_humedo');

        return response()->json(['res' => false, 'pesajes' => $pesajes, 'sumaBruto' => $sumaBruto, 'sumaNeto' => $sumaNeto]);

    }

    public function store(CreatePesajeVentaRequest $request)
    {
        $input = $request->all();
        $ventaId = $request->venta_id;
        $venta = Venta::find($ventaId);
        if($venta->estado!==EstadoVenta::EnProceso)
            return response()->json(['res' => false, 'message' => 'No se puede modificar la venta porque está liquidada']);
        $sumaPesoBruto = PesajeVenta::whereVentaId($ventaId)->sum('peso_bruto_humedo');
//
//        if(($venta->suma_peso_bruto_humedo - ($request->peso_bruto_humedo + $sumaPesoBruto)) < 0)
//            return response()->json(['res' => false, 'message' => 'El peso bruto húmedo no puede superar al total']);
//        else{
            PesajeVenta::create($input);
            event(new AccionCompletaVenta("Modificado", "Pesaje agregado", $ventaId));
            return response()->json(['res' => true, 'message' => 'Pesaje registrado correctamente']);
//        }
    }

    public function destroy($id)
    {
        $pesajeVenta = PesajeVenta::whereId($id)
            ->whereHas('venta', function ($q) {
                $q->whereEstado(EstadoVenta::EnProceso);
            })->first();
        if (empty($pesajeVenta)) {
            return response()->json(['res' => false, 'message' => 'Pesaje no encontrado']);
        }
        $pesajeVenta->delete($id);
        event(new AccionCompletaVenta("Modificado", "Pesaje eliminado", $pesajeVenta->venta_id));

        return response()->json(['res' => true, 'message' => 'Pesaje eliminado correctamente']);
    }

    public function imprimirOrdenVenta($ventaId){
        $venta = Venta::find($ventaId);
        $pesajes = PesajeVenta::whereVentaId($ventaId)->with('chofer', 'vehiculo')
            ->orderBy('id')->get();

        $vistaurl = "ventas.orden_venta";
        $view = \View::make($vistaurl, compact('pesajes', 'venta'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('OrdenDeVenta-' . $venta->codigo_odv . '.pdf');
    }
}
