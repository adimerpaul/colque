<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Http\Requests\CreateAnticipoRequest;
use App\Models\Anticipo;
use App\Http\Controllers\AppBaseController;
use App\Models\Cliente;
use App\Models\FormularioLiquidacion;
use App\Models\PagoMovimiento;
use App\Models\User;
use App\Patrones\Estado;
use Illuminate\Http\Request;
use Flash;
use Response;
use Luecano\NumeroALetras\NumeroALetras;

class AnticipoController extends AppBaseController
{

    public function index(Request $request)
    {
        $formulario_id = $request->formulario_id;

        $anticipos = Anticipo::whereFormularioLiquidacionId($formulario_id)
            ->orderBy('fecha')->get();
        return $anticipos;
    }


    public function store(CreateAnticipoRequest $request)
    {
        $input = $request->all();
        $formulario_id = $request->formulario_liquidacion_id;
        $contador = Anticipo::whereFormularioLiquidacionId($formulario_id)->count();

        $anticipos = Anticipo::whereFormularioLiquidacionId($formulario_id)->orderBy('fecha')->get();
        if ($contador < 10) {
            $formulario = FormularioLiquidacion::find($formulario_id);
            $valor_neto = $formulario->valor_neto_venta;
            if ($valor_neto == 0) {
                $valor_neto = 99999999999;
            }
            $suma_anticipos = Anticipo::whereFormularioLiquidacionId($formulario_id)->sum('monto');
            if ($valor_neto > $suma_anticipos + $request->monto) {
                $anticipo = Anticipo::create($input);

                $this->actualizarEnFormulario($formulario_id);

                event(new AccionCompleta("Modificado", "Anticipo pagado, fecha: " . $anticipo->fecha_formato . ", monto: " . $anticipo->monto, $formulario_id));
                return response()->json(['res' => true, 'anticipos' => $anticipos, 'message' => 'Anticipo registrado correctamente']);
            } else {
                return response()->json(['res' => false, 'anticipos' => $anticipos, 'message' => 'La suma de anticipos no puede ser mayor al valor neto de venta']);
            }

        } else {
            return response()->json(['res' => false, 'anticipos' => $anticipos, 'message' => 'No se puede agregar más anticipos']);
        }
    }

    public function destroy($id)
    {
        $anticipo = Anticipo::find($id);
        if (empty($anticipo)) {
            return response()->json(['res' => false, 'message' => 'Anticipo no encontrado']);
        }
        if ($anticipo->es_cancelado) {
            return response()->json(['res' => false, 'message' => 'No se puede eliminar un anticipo ya cancelado']);
        }
        $anticipo->delete($id);
        $this->actualizarEnFormulario($anticipo->formulario_liquidacion_id);

        event(new AccionCompleta("Modificado", "Anticipo eliminado", $anticipo->formulario_liquidacion_id));

        return response()->json(['res' => true, 'message' => 'Anticipo eliminado correctamente']);
    }

    public function imprimir($id)
    {
        $anticipo = Anticipo::with('formularioLiquidacion')->find($id);
        $cliente = Cliente::find($anticipo->cliente_pago);
        $fecha = $anticipo->created_at;

        $pago = PagoMovimiento::whereOrigenId($id)->whereOrigenType(Anticipo::class)->orderByDesc('id')->first();
        if ($pago)
            $fecha = $pago->created_at;

        $historial = Anticipo::whereFormularioLiquidacionId($anticipo->formularioLiquidacion->id)->whereEsCancelado(true)
            ->where('id', '<=', $id)->orderBy('id')->get();

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($anticipo->monto, 2, 'BOLIVIANOS', 'CENTAVOS');

        $vistaurl = "anticipos.imprimir";
        $view = \View::make($vistaurl, compact('anticipo', 'cliente', 'historial', 'literal', 'fecha', 'pago'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboAnticipo-' . $anticipo->id . '-' . $anticipo->formularioLiquidacion->lote . '.pdf');
    }

    public function generarBoletaAnticipo($id)
    {
        $anticipo = Anticipo::with('formularioLiquidacion')->find($id);
        $cliente = Cliente::find($anticipo->cliente_pago);
        $fecha = $anticipo->created_at;

        $pago = PagoMovimiento::whereOrigenId($id)->whereOrigenType(Anticipo::class)->orderByDesc('id')->first();
        if ($pago)
            $fecha = $pago->created_at;

        $historial = Anticipo::whereFormularioLiquidacionId($anticipo->formularioLiquidacion->id)
            ->where('id', '<=', $id)->orderBy('id')->get();

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($anticipo->monto, 2, 'BOLIVIANOS', 'CENTAVOS');

        $firmaCaja = '';
        if ($anticipo->usuario_pago) {
            $usuario = User::find($anticipo->usuario_pago);
            $firmaCaja = $usuario->personal->firma;
            $nombreCaja = $usuario->personal->nombre_completo;
        }

        $vistaurl = "anticipos.recibo_generado";
        $view = \View::make($vistaurl, compact('anticipo', 'cliente', 'historial', 'literal', 'fecha', 'firmaCaja', 'nombreCaja'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $file_url = public_path() . "/documents/" . $anticipo->formularioLiquidacion->id . '.pdf';
        file_put_contents($file_url, $pdf->output());
//        return $pdf->stream('Pesaje ' . $formularioLiquidacion->lote . '.pdf');
        return $pdf->output();

    }

    private function actualizarEnFormulario($formId)
    {
        $formulario = FormularioLiquidacion::find($formId);
        $formulario->update(['total_anticipo' => $formulario->totales['total_anticipos']]);
    }

}
