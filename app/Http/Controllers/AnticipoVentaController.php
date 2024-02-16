<?php

namespace App\Http\Controllers;

use App\Events\AccionCompletaVenta;
use App\Http\Requests\CreateAnticipoRequest;
use App\Models\Anticipo;
use App\Models\AnticipoVenta;
use App\Http\Controllers\AppBaseController;
use App\Models\DocumentoCompra;
use App\Models\FormularioLiquidacion;
use App\Models\PagoMovimiento;
use App\Models\User;
use App\Models\Venta;
use App\Patrones\TipoPago;
use Illuminate\Http\Request;
use Flash;
use Response;
use DB;
use Luecano\NumeroALetras\NumeroALetras;

class AnticipoVentaController extends AppBaseController
{

    public function index(Request $request)
    {
        $venta_id = $request->venta_id;

        $anticipos = AnticipoVenta::whereVentaId($venta_id)
            ->orderBy('created_at')->get();
        return $anticipos;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $venta_id = $request->venta_id;

        $venta = Venta::find($venta_id);

        if(is_null($venta->comprador_id))
            return response()->json(['res' => false, 'message' => 'Registre primero el comprador']);

        $anticipos = AnticipoVenta::whereVentaId($venta_id)->orderBy('created_at')->get();

        $anticipo = AnticipoVenta::create($input);

        event(new AccionCompletaVenta("Modificado", "Anticipo pagado, monto: " . $anticipo->monto, $venta_id));
        return response()->json(['res' => true, 'anticipos' => $anticipos, 'message' => 'Anticipo registrado correctamente']);

    }

    public function destroy($id)
    {
        $anticipo = AnticipoVenta::find($id);
        if (empty($anticipo)) {
            return response()->json(['res' => false, 'message' => 'Anticipo no encontrado']);
        }
        if ($anticipo->es_cancelado) {
            return response()->json(['res' => false, 'message' => 'No se puede eliminar un anticipo ya cancelado']);
        }
        $anticipo->delete($id);

        event(new AccionCompletaVenta("Modificado", "Anticipo eliminado", $anticipo->venta_id));

        return response()->json(['res' => true, 'message' => 'Anticipo eliminado correctamente']);
    }

    public function imprimir($id)
    {
        $anticipo = AnticipoVenta::with('venta')->find($id);
        $fecha = $anticipo->created_at;

        $pago = PagoMovimiento::whereOrigenId($id)->whereOrigenType(AnticipoVenta::class)->orderByDesc('id')->first();
        if ($pago)
            $fecha = $pago->created_at;

        $historial = AnticipoVenta::whereVentaId($anticipo->venta_id)->whereEsCancelado(true)
            ->where('id', '<=', $id)->orderBy('id')->get();

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($anticipo->monto, 2, 'BOLIVIANOS', 'CENTAVOS');

        $vistaurl = "anticipos_ventas.imprimir";
        $view = \View::make($vistaurl, compact('anticipo',  'historial', 'literal', 'fecha', 'pago'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboAnticipoVenta-' . $anticipo->id . '-' . $anticipo->venta->lote . '.pdf');
    }


    public function getAnticiposCaja(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 weeks'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $esCancelado = $request->txtEstado;

        if (is_null($esCancelado)) {
            $esCancelado = false;
        }
        $fechaFinal = $fecha_final;
            $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        if ($esCancelado) {
            $anticipos = PagoMovimiento::
            join('anticipo_venta', 'anticipo_venta.id', '=', 'pago_movimiento.origen_id')
                ->join('venta', 'venta.id', '=', 'anticipo_venta.venta_id')
                ->join('comprador', 'comprador.id', '=', 'venta.comprador_id')
                ->where('pago_movimiento.origen_type', AnticipoVenta::class)
                ->where('anticipo_venta.es_cancelado', true)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where(\DB::raw("concat(pago_movimiento.monto, ' BOB')"), 'ilike', "%{$txtBuscar}%")
                        ->orwhere('producto', 'ilike', "%{$txtBuscar}%")
                        ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(venta.anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                        ->orWhere('comprador.razon_social', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('comprador.nit', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('pago_movimiento.codigo', 'ilike', "%{$txtBuscar}%");
                })
                ->whereBetween('pago_movimiento.created_at', [$fecha_inicial, $fecha_final])
                ->orderBy('pago_movimiento.id')
                ->select('pago_movimiento.codigo', 'pago_movimiento.created_at',  'comprador.nit', 'alta',
                    'comprador.razon_social', 'venta.producto', 'pago_movimiento.monto', 'pago_movimiento.origen_id',
                    DB::raw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(venta.anio as varchar),3,4))) as lote"))
                ->paginate(50);
        } else {
            $anticipos = AnticipoVenta::
            where('es_cancelado', false)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where(\DB::raw("concat(monto, ' BOB')"), 'ilike', "%{$txtBuscar}%")
                        ->orWhereHas('venta', function ($q) use ($txtBuscar) {
                            $q->where('producto', 'ilike', "%{$txtBuscar}%")
                                ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(venta.anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                                ->orWhereHas('comprador', function ($q) use ($txtBuscar) {
                                    $q->where('razon_social', 'ilike', "%{$txtBuscar}%")
                                        ->orWhere('nit', 'ilike', "%{$txtBuscar}%");
                                });
                        });
                })
                ->whereBetween('created_at', [$fecha_inicial, $fecha_final])
                ->orderByDesc('id')
                ->paginate();
        }

        return view('anticipos_ventas.caja_anticipos')->with('anticipos', $anticipos)->with('esCancelado', $esCancelado)
            ->with('fechaInicial', $fecha_inicial)->with('fechaFinal', $fechaFinal);
    }

    public
    function registrarCobro(Request $request)
    {
        \DB::beginTransaction();
        try {
            $anticipoId = $request->idAnticipo;
            $anticipo = AnticipoVenta::find($anticipoId);
            if ($anticipo->es_cancelado) {
                \DB::rollBack();

                Flash::error('El anticipo ya fue cobrado anteriormente.');

                return redirect(route('anticipos_ventas.pagos'));
            }
            AnticipoVenta::where('id', $anticipoId)->update(['es_cancelado' => true
//                , 'usuario_pago' => auth()->user()->id
            ]);

            $campos['monto'] = $anticipo->monto;
            $campos['metodo'] = $request->metodo;
            if ($request->metodo == TipoPago::CuentaBancaria){
                $campos['banco'] = $request->banco;
                $campos['glosa'] = 'Cobro por anticipo de Lote ' . $anticipo->venta->lote . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
            }
            else
                $campos['glosa'] = 'Cobro por anticipo de Lote ' . $anticipo->venta->lote;
//        $campos['glosa'] = 'Liquidación por anticipo de Lote ' . $anticipo->venta->lote;
            $campos['origen_type'] = AnticipoVenta::class;
            $campos['origen_id'] = $anticipoId;
            $obj  = new MovimientoController();
            $campos['codigo'] = $obj->getCodigo('Ingreso');
            $campos['anio'] = date('y');
            if(date('m')>=10)
                $campos['anio'] =$campos['anio'] +1;

            $objMov= new MovimientoController();
            $campos['numero'] = $objMov->proximoOrden();
            $pago = PagoMovimiento::create($campos);



            \DB::commit();
            Flash::success('Anticipo pagado correctamente.');

            echo "<script>
            window.location.href = '/cobros/anticipos-ventas';
            window.open('/anticipos-ventas/'+'$anticipoId'+'/imprimir', '_blank');
                </script>";
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }
}
