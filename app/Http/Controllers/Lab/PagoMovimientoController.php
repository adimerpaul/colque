<?php

namespace App\Http\Controllers\Lab;

use App\Models\Lab\CuentaContable;
use App\Models\Lab\Movimiento;
use App\Models\Lab\PagoMovimiento;
use App\Models\Lab\Recepcion;
use App\Http\Controllers\AppBaseController;
use App\Patrones\TipoPago;
use Illuminate\Http\Request;
use Flash;
use Luecano\NumeroALetras\NumeroALetras;

class PagoMovimientoController extends AppBaseController
{

    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $esCancelado = $request->txtEstado;

        if (is_null($esCancelado)) {
            $esCancelado = false;
        }
        $pagos = PagoMovimiento::
        where('es_cancelado', $esCancelado)
            ->where('glosa', 'ilike', "%{$txtBuscar}%")
            ->where(function ($q) use ($fecha_inicial, $fechaFin, $esCancelado) {
                if ($esCancelado) {
                    $q->whereBetween('fecha', [$fecha_inicial, $fechaFin]);
                } else
                    $q->whereBetween('updated_at', [$fecha_inicial, $fechaFin]);
            })
            ->orderByDesc('updated_at')
            ->paginate(50);

        return view('lab.caja.index')->with('pagos', $pagos)->with('esCancelado', $esCancelado);
    }

    public function getCodigo($tipo)
    {
        $anio = date('Y');
//        $mes = date('m');
//        if ($mes < '10') {
//            $fechaFin = date('Y-m-d H:i:s', strtotime($anio . '-09-30 23:59:59'));
//            $anio = date('Y', strtotime($anio . ' - 1 years'));
//            $fechaInicio = date('Y-m-d H:i:s', strtotime($anio . '-10-01 00:00:00'));
//        } else {
            $fechaInicio = date('Y-m-d H:i:s', strtotime($anio . '-01-01 00:00:00'));
//            $anio = date('Y', strtotime($anio . ' + 1 years'));
            $fechaFin = date('Y-m-d H:i:s', strtotime($anio . '-12-31 23:59:59'));
//        }
        $tipo = substr($tipo, 0, 1);
        $contador = PagoMovimiento::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('codigo', 'ilike', "%{$tipo}%")
            ->whereNotNull('fecha')
            ->orderByDesc('fecha')
            ->select('codigo')
            ->first();

        $numero = '00001';

        if ($contador) {
            $contador = substr($contador->codigo, 2);
            $contador = $contador + 1;
            $numero = str_pad($contador, 5, "0", STR_PAD_LEFT);
        }

        return (sprintf("%s%s%s", 'C', $tipo, $numero));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $movimiento = Movimiento::create($input);

        $input["tipo"] = 'Egreso';
        $input["origen_id"] = $movimiento->id;
        $input["origen_type"] = Movimiento::class;
        $input['es_cancelado'] = true;
        $input['fecha'] = date('Y-m-d H:i:s');
        $input['codigo'] = $this->getCodigo('Egreso');
        $input['anio'] = date('y');
//        if (date('m') >= 10)
//            $input['anio'] = $input['anio'] + 1;

        PagoMovimiento::create($input);
        Flash::success('Pago registrado correctamente');
        return redirect(route('cajas-lab.index'));
    }

    public function storeAnalisis($monto, $recepcionId, $glosa)
    {
        if ($monto > 0.00) {
            $input['monto'] = $monto;
            $input["glosa"] = $glosa;
            $input["tipo"] = "Ingreso";
            $input['anio'] = date('y');
            if (date('m') >= 10)
                $input['anio'] = $input['anio'] + 1;
            $input["origen_id"] = $recepcionId;
            $input["origen_type"] = Recepcion::class;

            PagoMovimiento::create($input);
        }
    }

    public function registrarPago(Request $request)
    {
        $codigo = $this->getCodigo('Ingreso');
        PagoMovimiento::whereId($request->idPago)->
        update(["es_cancelado" => true, "codigo" => $codigo, "metodo" => $request->metodo,
            'fecha' => date('Y-m-d H:i:s'), "comprobante_banco" => $request->metodo == TipoPago::CuentaBancaria ? $request->numero_recibo : null]);
        Flash::success('Pago registrado correctamente');
        return redirect(route('cajas-lab.index'));
//        echo "<script>
//            window.location.href = '/cajas-lab';
//            window.open('/imprimir-comprobante-lab/'+'$request->idPago', '_blank');
//                </script>";
    }

    public function imprimir($id)
    {
        $pago = PagoMovimiento::find($id);
        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($pago->monto, 2, 'BOLIVIANOS', 'CENTAVOS');

        $vistaurl = "lab.caja.imprimir";
        $view = \View::make($vistaurl, compact('pago', 'literal'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('recibo-' . $pago->codigo . '.pdf');
    }

    public function create()
    {
        return view('lab.caja.create');
    }


    public function anular(Request $request)
    {
        $id = $request->idPago;
        \DB::beginTransaction();
        try {
            $pago = PagoMovimiento::find($id);
            $pago->alta = false;
            $pago->es_cancelado = false;
            $pago->motivo_anulacion = $request->motivo_anulacion;
            $pago->save();

            \DB::commit();
            Flash::success('Pago anulado correctamente.');
            return redirect(route('cajas-lab.index'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }
    public function getCatalogo($tipo)
    {
        $cuentas = CuentaContable::whereTipo($tipo)->orderBy('descripcion')->get()->pluck('descripcion', 'descripcion');
        return json_encode($cuentas);
    }
}
