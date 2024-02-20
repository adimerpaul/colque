<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Models\Bono;
use App\Http\Controllers\AppBaseController;
use App\Models\Cliente;
use App\Models\FormularioLiquidacion;
use App\Models\Movimiento;
use App\Models\PagoMovimiento;
use App\Patrones\ClaseDevolucion;
use Illuminate\Http\Request;
use Flash;
use Response;
use Luecano\NumeroALetras\NumeroALetras;

class BonoController extends AppBaseController
{
    public function index(Request $request)
    {
        $formulario_id = $request->formulario_id;
        $bonos = Bono::whereFormularioLiquidacionId($formulario_id)->orderByDesc('id')->get();
        return $bonos;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['fecha'] = date('Y-m-d');
        $input['es_aprobado'] = true;

        if($request->clase==ClaseDevolucion::Analisis)
            $input['es_cancelado'] = true;

        $bono = Bono::create($input);
        event(new AccionCompleta("Devolución Registrada", "Devolución Registrada", $bono->formulario_liquidacion_id));
        return response()->json(['res' => true, 'message' => 'Bono registrado correctamente']);
    }

    public function show($id)
    {
        $devolucion = Bono::whereId($id)->whereEsAprobado(false)->first();

        if (empty($devolucion)) {
            Flash::error('Devolución no encontrada');

            return redirect(route('home'));
        }

        return view('bonos.aprobar')->with('devolucion', $devolucion);
    }

    public function aprobar(Request $request)
    {
        $id = $request->id;
        $devolucion = Bono::whereId($id)->whereEsAprobado(false)->first();

        if (empty($devolucion)) {
            Flash::error('Devolucion no encontrada');

            return redirect(route('home'));
        }
        $devolucion->update(['es_aprobado' => true]);
        Flash::success('Devolución aprobada');

        return redirect(route('home'));
    }
    public function destroy($id)
    {
        $bono = Bono::find($id);

        if ($bono->es_cancelado)
            return response()->json(['res' => false, 'message' => 'No se puede eliminar, porque ya fue pagado']);

        $bono->delete($id);
        event(new AccionCompleta("Devolución Eliminada", "Devolución eliminada", $bono->formulario_liquidacion_id));
        return response()->json(['res' => true, 'message' => 'Devolución eliminada correctamente']);
    }

    public function imprimir($bonoId)
    {
        $devolucion = Bono::find($bonoId);
        $fecha = $devolucion->created_at;

        $pago = PagoMovimiento::whereOrigenId($bonoId)->where('origen_type', Bono::class)->orderByDesc('id')->first();
        if($pago)
            $fecha = $pago->created_at;

        $bonos = PagoMovimiento::
        join('bono', 'bono.id', '=', 'pago_movimiento.origen_id')
            ->where('bono.es_cancelado', true)
            ->where('origen_type', Bono::class)
            ->where('pago_movimiento.id', '<=', $pago->id)
            ->whereAlta(true)
            ->where('bono.formulario_liquidacion_id', $devolucion->formulario_liquidacion_id)
            ->select('pago_movimiento.created_at', 'motivo', 'pago_movimiento.monto')
            ->paginate();

//        $bonos=Bono::whereFormularioLiquidacionId($bono->formulario_liquidacion_id)->get();


        $cliente = Cliente::find($devolucion->formularioLiquidacion->cliente_id);

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($devolucion->monto, 2, 'BOLIVIANOS', 'CENTAVOS');


        $vistaurl = "bonos.imprimir";
        $view = \View::make($vistaurl, compact('cliente', 'bonos', 'literal', 'devolucion', 'fecha'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboDevolucion-' . $devolucion->formularioLiquidacion->lote . '.pdf');
    }

    public function getTotal($formularioId){
        return Bono::whereFormularioLiquidacionId($formularioId)->sum('monto');
    }

    public function getCantidadDevoluciones($formularioId){
        $contador = Bono::whereFormularioLiquidacionId($formularioId)->whereClase(ClaseDevolucion::Interno)->count();
        return $contador;
    }

    public function getSuma($formularioId, $tipo){
        return Bono::whereFormularioLiquidacionId($formularioId)->whereTipoMotivo($tipo)->sum('monto');
    }

    public function getDevoluciones($formId)
    {
        $devoluciones = Bono::whereFormularioLiquidacionId($formId)->orderByDesc('id')->get();

        return view('formulario_liquidacions.devoluciones')
            ->with('devoluciones', $devoluciones);
    }
}
