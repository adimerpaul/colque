<?php

namespace App\Http\Controllers;

use App\Models\Anticipo;
use App\Models\Bono;
use App\Models\Costo;
use App\Models\CostoVenta;
use App\Models\CuentaCobrar;
use App\Models\DocumentoCompra;
use App\Models\FormularioLiquidacion;
use App\Models\Movimiento;
use App\Models\MovimientoCatalogo;
use App\Models\PagoDolar;
use App\Models\PagoMovimiento;
use App\Models\PagoRetencion;
use App\Models\Prestamo;
use App\Models\Retencion;
use App\Models\SaldoDiario;
use App\Models\Venta;
use App\Patrones\Banco;
use App\Patrones\Estado;
use App\Patrones\EstadoVenta;
use App\Patrones\Fachada;
use App\Patrones\TipoPago;
use App\Patrones\TipoTransferencia;
use Illuminate\Http\Request;
use Flash;
use Luecano\NumeroALetras\NumeroALetras;
use DB;

class MovimientoController extends AppBaseController
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
        if (!$esCancelado) {
            $pagos = Movimiento::
            where('es_cancelado', $esCancelado)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where('motivo', 'ilike', "%{$txtBuscar}%")
                        ->orWhereHas('proveedor', function ($q) use ($txtBuscar) {
                            $q->where('nombre', 'ilike', "%{$txtBuscar}%")
                                ->orWhere('nit', 'ilike', "%{$txtBuscar}%")
                                ->orWhere('empresa', 'ilike', "%{$txtBuscar}%");
                        });
                })
                ->where('motivo', 'ilike', "%{$request->txtDescripcion}%")
                ->whereBetween('updated_at', [$fecha_inicial, $fechaFin])
                ->whereEsAprobado(true)
                ->orderByDesc('updated_at')
                ->paginate(50);
        } else {
            $pagos = Movimiento::
            join('proveedor', 'movimiento.proveedor_id', '=', 'proveedor.id')
                ->join('pago_movimiento', 'pago_movimiento.origen_id', '=', 'movimiento.id')
                ->where('pago_movimiento.origen_type', Movimiento::class)
                ->where('movimiento.es_cancelado', $esCancelado)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where('glosa', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('codigo', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('nombre', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('nit', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('empresa', 'ilike', "%{$txtBuscar}%");

                })
                ->where('movimiento.oficina', 'ilike', "%{$request->txtOficina}%")
                ->where('motivo', 'ilike', "%{$request->txtDescripcion}%")
                ->whereBetween('movimiento.updated_at', [$fecha_inicial, $fechaFin])
                ->where('movimiento.es_aprobado', true)
                ->orderByDesc('movimiento.updated_at')
                ->paginate(50);

        }

        return view('movimientos.index')->with('pagos', $pagos)->with('esCancelado', $esCancelado);
    }

    public function getSumaMontos($movimientoId)
    {
        $pagos = PagoMovimiento::whereOrigenId($movimientoId)->whereOrigenType(Movimiento::class)->whereAlta(true)->sum('monto');
        return $pagos;
    }

    public function create()
    {
        return view('movimientos.create');
    }

    public function getCodigo($tipo)
    {
        $anio = date('Y');
        $mes = date('m');
        if ($mes < '10') {
            $fechaFin = date('Y-m-d H:i:s', strtotime($anio . '-09-30 23:59:59'));
            $anio = date('Y', strtotime($anio . ' - 1 years'));
            $fechaInicio = date('Y-m-d H:i:s', strtotime($anio . '-10-01 00:00:00'));
        } else {
            $fechaInicio = date('Y-m-d H:i:s', strtotime($anio . '-10-01 00:00:00'));
            $anio = date('Y', strtotime($anio . ' + 1 years'));
            $fechaFin = date('Y-m-d H:i:s', strtotime($anio . '-09-30 23:59:59'));
        }
        $tipo = substr($tipo, 0, 1);
        $contador = PagoMovimiento::whereBetween('created_at', [$fechaInicio, $fechaFin])
//            ->whereHas('origen', function ($q) use ($tipo) {
//                $q->where('tipo', $tipo);
//            })
            ->where('codigo', 'ilike', "%{$tipo}%")
            ->orderByDesc('id')
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

    public function storeTransferencia(Request $request)
    {
        $input = $request->all();
        $input['user_id'] = auth()->user()->id;
        $input['proveedor_id'] = 118;
        $input['es_aprobado'] = true;
        if ($request->tipo_transferencia == TipoTransferencia::CuentaBnbACaja or $request->tipo_transferencia == TipoTransferencia::CuentaEconomicoACaja) {
            $input['motivo'] = "Transferencia interna";
            $input['total'] = $request->monto;
            $input['es_cancelado'] = true;
            $input['tipo'] = 'Egreso';
            $movimiento1 = Movimiento::create($input);

            $input['codigo'] = $this->getCodigo('Egreso');
            $input['origen_type'] = Movimiento::class;
            $input['origen_id'] = $movimiento1->id;
            $input['metodo'] = TipoPago::CuentaBancaria;
            if ($request->tipo_transferencia == TipoTransferencia::CuentaBnbACaja) {
                $input['glosa'] = 'Transferencia interna de Cuenta bancaria BNB a Caja, con recibo ' . $request->numero_recibo;
                $input['banco'] = 'BNB';
            } else {
                $input['glosa'] = 'Transferencia interna de Cuenta bancaria Economico a Caja, con recibo ' . $request->numero_recibo;
                $input['banco'] = 'Economico';
            }

            $input['anio'] = date('y');
            if (date('m') >= 10)
                $input['anio'] = $input['anio'] + 1;

            $input['numero'] = $this->proximoOrden();
            $pagoMovimiento1 = PagoMovimiento::create($input);

            sleep(2);

            $input['tipo'] = 'Ingreso';
            $movimiento2 = Movimiento::create($input);

            $input['codigo'] = $this->getCodigo('Ingreso');
            $input['origen_id'] = $movimiento2->id;
            $input['metodo'] = TipoPago::Efectivo;
            if ($request->tipo_transferencia == TipoTransferencia::CuentaBnbACaja)
                $input['glosa'] = 'Transferencia interna de Cuenta bancaria BNB a Caja';
            else
                $input['glosa'] = 'Transferencia interna de Cuenta bancaria Economico a Caja';

            $input['numero'] = $this->proximoOrden();
            $pagoMovimiento2 = PagoMovimiento::create($input);
        } elseif ($request->tipo_transferencia == TipoTransferencia::CajaACuentaBnb or $request->tipo_transferencia == TipoTransferencia::CajaACuentaEconomico) {
            $input['motivo'] = "Transferencia interna";
            $input['total'] = $request->monto;
            $input['es_cancelado'] = true;
            $input['tipo'] = 'Egreso';

            $movimiento1 = Movimiento::create($input);

            $input['codigo'] = $this->getCodigo('Egreso');
            $input['origen_type'] = Movimiento::class;
            $input['origen_id'] = $movimiento1->id;
            $input['metodo'] = TipoPago::Efectivo;

            if ($request->tipo_transferencia == TipoTransferencia::CajaACuentaBnb)
                $input['glosa'] = 'Transferencia interna de Caja a Cuenta bancaria BNB';
            else
                $input['glosa'] = 'Transferencia interna de Caja a Cuenta bancaria Economico';

            $input['anio'] = date('y');
            if (date('m') >= 10)
                $input['anio'] = $input['anio'] + 1;

            $input['numero'] = $this->proximoOrden();
            $pagoMovimiento1 = PagoMovimiento::create($input);

            sleep(2);

            $input['tipo'] = 'Ingreso';
            $movimiento2 = Movimiento::create($input);

            $input['codigo'] = $this->getCodigo('Ingreso');
            $input['origen_id'] = $movimiento2->id;
            $input['metodo'] = TipoPago::CuentaBancaria;
            if ($request->tipo_transferencia == TipoTransferencia::CajaACuentaBnb) {
                $input['glosa'] = 'Transferencia interna de Caja a Cuenta bancaria BNB, con recibo ' . $request->numero_recibo;
                $input['banco'] = 'BNB';
            } else {
                $input['glosa'] = 'Transferencia interna de Caja a Cuenta bancaria Economico, con recibo ' . $request->numero_recibo;
                $input['banco'] = 'Economico';
            }
            $input['numero'] = $this->proximoOrden();
            $pagoMovimiento2 = PagoMovimiento::create($input);
        } else {
            $input['motivo'] = "Transferencia interna";
            $input['total'] = $request->monto;
            $input['es_cancelado'] = true;
            $input['tipo'] = 'Egreso';
            $movimiento1 = Movimiento::create($input);

            $input['codigo'] = $this->getCodigo('Egreso');
            $input['origen_type'] = Movimiento::class;
            $input['origen_id'] = $movimiento1->id;
            $input['metodo'] = TipoPago::CuentaBancaria;
            if ($request->tipo_transferencia == TipoTransferencia::CuentaBnbACuentaEconomico) {
                $input['glosa'] = 'Transferencia interna de Cuenta bancaria BNB a Cuenta bancaria Economico';
                $input['banco'] = 'BNB';
            } else {
                $input['glosa'] = 'Transferencia interna de Cuenta bancaria Economico a Cuenta bancaria BNB';
                $input['banco'] = 'Economico';
            }

            $input['anio'] = date('y');
            if (date('m') >= 10)
                $input['anio'] = $input['anio'] + 1;

            $input['numero'] = $this->proximoOrden();
            $pagoMovimiento1 = PagoMovimiento::create($input);

            sleep(2);

            $input['tipo'] = 'Ingreso';
            $movimiento2 = Movimiento::create($input);

            $input['codigo'] = $this->getCodigo('Ingreso');
            $input['origen_id'] = $movimiento2->id;
            $input['metodo'] = TipoPago::CuentaBancaria;
            if ($request->tipo_transferencia == TipoTransferencia::CuentaBnbACuentaEconomico) {
                $input['glosa'] = 'Transferencia interna de Cuenta bancaria BNB a Cuenta bancaria Economico, con recibo ' . $request->numero_recibo;
                $input['banco'] = 'Economico';
            } else {
                $input['glosa'] = 'Transferencia interna de Cuenta bancaria Economico a Cuenta bancaria BNB, con recibo ' . $request->numero_recibo;
                $input['banco'] = 'BNB';
            }
            $input['numero'] = $this->proximoOrden();
            $pagoMovimiento2 = PagoMovimiento::create($input);
        }

        Flash::success('Pago registrado correctamente.');

        echo "<script>
            window.location.href = '/movimientos';
            window.open('/movimientos/'+'$pagoMovimiento1->id'+'/imprimir', '_blank');

            window.open('/movimientos/'+'$pagoMovimiento2->id'+'/imprimir', '_blank');
                </script>";

    }

    public function store(Request $request)
    {
        // if (auth()->user()->personal->es_jefe) {
        $ruta = 'movimientos.create';
        $input = $request->all();
        $input['es_aprobado'] = true;

        $input['motivo'] = trim(sprintf("%s%s%s", $request->descripcion, '. ', $request->glosa));
        $input['user_id'] = auth()->user()->id;

        if ($request->lote != "") {
            if ($request->tipoLote == 'Compra') {
                $lote = FormularioLiquidacion::find($request->lote);
                $input['origen_type'] = FormularioLiquidacion::class;

//                if ($request->descripcion == 'ANÁLISIS QUÍMICO EN LOTE') {
//                    $obj = new CostoController();
//                    $obj->actualizarLaboratorio('laboratorio', $request->lote, $request->monto);
//                }
//
//                if ($request->descripcion == 'ANÁLISIS QUÍMICO DIRIMICIÓN EN LOTE') {
//                    $obj = new CostoController();
//                    $obj->actualizarLaboratorio('dirimicion', $request->lote, $request->monto);
//                }
            } else {
                $lote = Venta::find($request->lote);
                $input['origen_type'] = Venta::class;
//                $objCostoVenta = new CostoVentaController();
//                $objCostoVenta->agregarCosto($request->lote, $request->monto, substr($request->descripcion, 0, -8));

            }
            $input['origen_id'] = $request->lote;
            $input['motivo'] = trim(sprintf("%s%s%s%s%s", $request->descripcion, ' ', $lote->lote, '. ', $request->glosa));
        }


        if ($request->tipo == 'Ingreso' or $request->monto <= 300) {
            $input['es_aprobado'] = true;
        }
        if ($request->tipo == 'Ingreso')
            $ruta = 'movimientos.index';

        Movimiento::create($input);

        Flash::success('Solicitud registrada correctamente.');
        return redirect(route($ruta));

    }

    public function registrarPagoTerceros(Request $request)
    {
        \DB::beginTransaction();
        try {
            $input = $request->all();
            $id = $request->idMovimiento;
            $movimiento = Movimiento::find($id);
            if ($movimiento->origen_type == FormularioLiquidacion::class) {
                if (str_contains($movimiento->motivo, 'ANÁLISIS QUÍMICO EN LOTE')) {
                    $obj = new CostoController();
                    $obj->actualizarLaboratorio('laboratorio', $movimiento->origen_id, $movimiento->monto);
                }

                if (str_contains($movimiento->motivo, 'ANÁLISIS QUÍMICO DIRIMICIÓN EN LOTE')) {
                    $obj = new CostoController();
                    $obj->actualizarLaboratorio('dirimicion', $movimiento->origen_id, $movimiento->monto);
                }
            } elseif ($movimiento->origen_type == Venta::class) {
                $objCostoVenta = new CostoVentaController();
                $objCostoVenta->agregarCosto($movimiento->origen_id, $movimiento->monto, strstr($movimiento->motivo, ' EN LOTE CM', true));

            }

            $movimiento->update(['es_cancelado' => true, 'factura' => $request->factura]);

            $input['origen_type'] = Movimiento::class;
            $input['codigo'] = $this->getCodigo($request->tipo);
            $input['origen_id'] = $id;
            $input['anio'] = date('y');
            if (date('m') >= 10)
                $input['anio'] = $input['anio'] + 1;

            if ($request->metodo == TipoPago::CuentaBancaria)
                $input['glosa'] = $movimiento->motivo . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
            else {
                $input['glosa'] = $movimiento->motivo;
                $input['banco'] = null;
            }
            $input['numero'] = $this->proximoOrden();
            $pagoMovimiento = PagoMovimiento::create($input);

            \DB::commit();
            Flash::success('Registro guardado correctamente.');

            echo "<script>
            window.location.href = '/movimientos';
            window.open('/movimientos/'+'$pagoMovimiento->id'+'/imprimir', '_blank');
                </script>";
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }


    public function edit($id)
    {
        $movimiento = Movimiento::find($id);

        if (empty($movimiento)) {
            Flash::error('Movimiento no encontrado');

            return redirect(route('movimientos.index'));
        }
        if ($movimiento->saldo_pago == 0) {
            Flash::error('Movimiento ya pagado');
            return redirect(route('movimientos.index'));
        }

        return view('movimientos.edit')->with('movimiento', $movimiento);
    }

//    public function update($id, Request $request)
//    {
//        $input = $request->all();
//
//        $movimiento = Movimiento::find($id);
//
//        if (empty($movimiento)) {
//            Flash::error('Movimiento no encontrado');
//
//            return redirect(route('movimientos.index'));
//        }
//
//        if ($movimiento->saldo_pago - $request->monto < 0) {
//            Flash::error('No se puede pagar un monto mayor al saldo');
//            return redirect(route('movimientos.edit', [$id]));
//        }
//        if ($movimiento->saldo_pago - $request->monto == 0) {
//            $input['es_cancelado'] = true;
//            $movimiento->update($input);
//        }
//
//        $input['origen_type'] = Movimiento::class;
//        $input['codigo'] = $this->getCodigo($movimiento->tipo);
//        $input['origen_id'] = $id;
//        if ($request->metodo == TipoPago::CuentaBancaria)
//            $input['glosa'] = $request->glosa . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
//        else
//            $input['glosa'] = $request->glosa;
//
//        $input['anio'] = date('y');
//        if (date('m') >= 10)
//            $input['anio'] = $input['anio'] + 1;
//
//        $pago = PagoMovimiento::create($input);
////        PagoMovimiento::where('id', $pago->id)->update(['saldo_caja' => $pago->saldo_pago, 'saldo_banco' => $pago->saldo_pago_banco]);
//
//        Flash::success('Pago registrado correctamente.');
//
//
//        echo "<script>
//            window.location.href = '/movimientos';
//            window.open('/movimientos/'+'$pago->id'+'/imprimir', '_blank');
//                </script>";
//    }

    public function imprimir($pagoId)
    {
        $pago = PagoMovimiento::find($pagoId);

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($pago->monto, 2, 'BOLIVIANOS', 'CENTAVOS');

//        $historial = PagoMovimiento::whereOrigenId($pago->origen_id)->whereAlta(true)->whereOrigenType($pago->origen_type)
//            ->where('id', '<=', $pago->id)->get();
        $vistaurl = "movimientos.imprimir";
        $view = \View::make($vistaurl, compact('pago', 'literal'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboPago-' . $pago->codigo . '.pdf');
    }

    public function reporte(Request $request)
    {

        $tipo = $request->tipo;
        $fechaInicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fechaInicial = $request->fecha_inicial;

        $fechaFinal = date('Y-m-d');
        if (isset($request->fecha_final))
            $fechaFinal = $request->fecha_final;
        $fechaFin = date('Y-m-d', strtotime($fechaFinal . ' + 1 days'));

        if ($tipo == TipoPago::Efectivo or $tipo == TipoPago::CuentaBancaria) {
            $pagos = PagoMovimiento::
            whereBetween('created_at', [$fechaInicial, $fechaFin])
                ->whereAlta(true)
                ->whereMetodo($tipo)
                ->orderBy('created_at')->orderBy('id')
                ->paginate(150);
        } else {
            $pagos = PagoMovimiento::
            whereBetween('created_at', [$fechaInicial, $fechaFin])
                ->whereAlta(true)
                ->whereHas('origen', function ($q) use ($tipo) {
                    if (!is_null($tipo)) {
                        $q->whereTipo($tipo);
                    }
                })
                ->orderBy('created_at')->orderBy('id')
                ->paginate(150);
        }
        $totales = PagoMovimiento::
        whereBetween('created_at', [$fechaInicial, $fechaFin])
            ->whereAlta(true)
            ->orderBy('created_at')->orderBy('id')
            ->get();

//        $caja = PagoMovimiento::whereBetween('created_at', [$fechaInicial, $fechaFin])
//            ->whereMetodo(TipoPago::Efectivo)
//            ->whereAlta(true)
//            ->orderBy('created_at')->orderBy('id')
//            ->get();
//        $banco = PagoMovimiento::whereBetween('created_at', [$fechaInicial, $fechaFin])
//            ->whereMetodo(TipoPago::CuentaBancaria)
//            ->whereAlta(true)
//            ->orderBy('created_at')->orderBy('id')
//            ->get();


        $totalIngresosCaja = 0;
        $totalEgresosCaja = 0;
        $totalTodoCaja = 0;
        $totalIngresosBnb = 0;
        $totalEgresosBnb = 0;
        $totalTodoBnb = 0;
        $totalIngresosEconomico = 0;
        $totalEgresosEconomico = 0;
        $totalTodoEconomico = 0;
        $saldoCaja = 0;
        $saldoBnb = 0;
        $saldoEconomico = 0;
        $inicialEconomico = $this->getSaldo($fechaInicial, TipoPago::CuentaBancaria, Banco::Economico);
        $inicialBnb = $this->getSaldo($fechaInicial, TipoPago::CuentaBancaria, Banco::BNB);
        $inicialCaja = $this->getSaldo($fechaInicial, TipoPago::Efectivo);

//        if ($totales->count() > 0) {
//            $inicialBanco =
//                ($totales[0]->metodo == TipoPago::CuentaBancaria ? $banco->first()->saldo_pago_banco : $caja->first()->saldo_pago_banco)
//                - ($totales[0]->metodo == TipoPago::CuentaBancaria ? $banco->first()->monto_signo : 0);
//
//            $inicialCaja =
//                ($totales[0]->metodo == TipoPago::Efectivo ? $caja->first()->saldo_pago : $banco->first()->saldo_pago)
//                - ($totales[0]->metodo == TipoPago::Efectivo ? $caja->first()->monto_signo : 0);
//        }
        if ($totales->count() > 0) {
            $saldoCaja = $totales->last()->saldo_pago;
            $saldoBnb = $totales->last()->saldo_bnb;
            $saldoEconomico = $totales->last()->saldo_economico;
        }
        foreach ($totales as $total) {
            if ($total->origen->tipo == 'Ingreso' and $total->monto_caja)
                $totalIngresosCaja = $totalIngresosCaja + $total->monto_caja;
            elseif ($total->origen->tipo == 'Ingreso' and $total->monto_bnb)
                $totalIngresosBnb = $totalIngresosBnb + $total->monto_bnb;
            elseif ($total->origen->tipo == 'Ingreso' and $total->monto_economico)
                $totalIngresosEconomico = $totalIngresosEconomico + $total->monto_economico;
            elseif ($total->origen->tipo == 'Egreso' and $total->monto_caja)
                $totalEgresosCaja = $totalEgresosCaja + $total->monto_caja;
            elseif ($total->origen->tipo == 'Egreso' and $total->monto_bnb)
                $totalEgresosBnb = $totalEgresosBnb + $total->monto_bnb;
            elseif ($total->origen->tipo == 'Egreso' and $total->monto_economico)
                $totalEgresosEconomico = $totalEgresosEconomico + $total->monto_economico;

            if ($total->monto_caja)
                $totalTodoCaja = $totalTodoCaja + ($total->origen->tipo == 'Ingreso' ? $total->monto_caja : ($total->monto_caja * -1));
            elseif ($total->monto_bnb)
                $totalTodoBnb = $totalTodoBnb + ($total->origen->tipo == 'Ingreso' ? $total->monto_bnb : ($total->monto_bnb * -1));
            elseif ($total->monto_economico)
                $totalTodoEconomico = $totalTodoEconomico + ($total->origen->tipo == 'Ingreso' ? $total->monto_economico : ($total->monto_economico * -1));
        }
        return view('movimientos.reporte', compact('pagos', 'fechaInicial', 'fechaFinal', 'tipo'
            ,
            'totalTodoBnb', 'totalTodoEconomico', 'totalTodoCaja', 'totalIngresosBnb', 'totalIngresosEconomico', 'totalIngresosCaja',
            'totalEgresosCaja', 'totalEgresosBnb', 'totalEgresosEconomico', 'saldoBnb', 'saldoEconomico', 'saldoCaja', 'inicialCaja', 'inicialBnb', 'inicialEconomico'
        ));

    }

    public function getAnticipos(Request $request)
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
        if ($esCancelado) {
            $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        }

        if ($esCancelado) {
            $anticipos = PagoMovimiento::
            join('anticipo', 'anticipo.id', '=', 'pago_movimiento.origen_id')
                ->join('formulario_liquidacion', 'formulario_liquidacion.id', '=', 'anticipo.formulario_liquidacion_id')
                ->join('cliente', 'cliente.id', '=', 'formulario_liquidacion.cliente_id')
                ->join('cooperativa', 'cooperativa.id', '=', 'cliente.cooperativa_id')
                ->where('pago_movimiento.origen_type', Anticipo::class)
                ->where('anticipo.es_cancelado', true)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where(\DB::raw("concat(pago_movimiento.monto, ' BOB')"), 'ilike', "%{$txtBuscar}%")
                        ->orwhere('producto', 'ilike', "%{$txtBuscar}%")
                        ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                        ->orWhere('cliente.nombre', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('cliente.nit', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('razon_social', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('pago_movimiento.codigo', 'ilike', "%{$txtBuscar}%");
                })
                ->whereBetween('pago_movimiento.created_at', [$fecha_inicial, $fecha_final])
                ->orderBy('pago_movimiento.id')
                ->select('pago_movimiento.codigo', 'pago_movimiento.created_at', 'cliente.nombre', 'cliente.nit', 'pago_movimiento.alta',
                    'pago_movimiento.metodo','pago_movimiento.banco',
                    'cooperativa.razon_social', 'formulario_liquidacion.producto', 'pago_movimiento.monto', 'pago_movimiento.origen_id',
                    DB::raw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4))) as lote"))
                ->paginate(100);
        } else {
            $anticipos = Anticipo::
            where('es_cancelado', false)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where(\DB::raw("concat(monto, ' BOB')"), 'ilike', "%{$txtBuscar}%")
                        ->orWhereHas('formularioLiquidacion', function ($q) use ($txtBuscar) {
                            $q->where('producto', 'ilike', "%{$txtBuscar}%")
                                ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                                ->orWhereHas('cliente', function ($q) use ($txtBuscar) {
                                    $q->where('nombre', 'ilike', "%{$txtBuscar}%")
                                        ->orWhere('nit', 'ilike', "%{$txtBuscar}%")
                                        ->orWhereHas('cooperativa', function ($q) use ($txtBuscar) {
                                            $q->where('razon_social', 'ilike', "%{$txtBuscar}%");
                                        });
                                });
                        });
                })
                ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                ->orderByDesc('id')
                ->paginate();
        }

        return view('anticipos.caja_anticipos')->with('anticipos', $anticipos)->with('esCancelado', $esCancelado)
            ->with('fechaInicial', $fecha_inicial)->with('fechaFinal', $fechaFinal);
    }

    public
    function registrarAnticipo(Request $request)
    {
        \DB::beginTransaction();
        try {
            $anticipoId = $request->idAnticipo;
            $anticipo = Anticipo::find($anticipoId);
            if ($anticipo->es_cancelado) {
                \DB::rollBack();

                Flash::error('El anticipo ya fue pagado anteriormente.');

                return redirect(route('pagos.anticipos'));
            }
            Anticipo::where('id', $anticipoId)->update(['es_cancelado' => true, 'usuario_pago' => auth()->user()->id]);

            $campos['monto'] = $anticipo->monto;
            $campos['metodo'] = $request->metodo;
            if ($request->metodo == TipoPago::CuentaBancaria) {
                $campos['banco'] = $request->banco;
                $campos['glosa'] = 'Liquidación por anticipo de Lote ' . $anticipo->formularioLiquidacion->lote . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
            } else
                $campos['glosa'] = 'Liquidación por anticipo de Lote ' . $anticipo->formularioLiquidacion->lote;
//        $campos['glosa'] = 'Liquidación por anticipo de Lote ' . $anticipo->formularioLiquidacion->lote;
            $campos['origen_type'] = Anticipo::class;
            $campos['origen_id'] = $anticipoId;
            $campos['codigo'] = $this->getCodigo('Egreso');
            $campos['anio'] = date('y');
            if (date('m') >= 10)
                $campos['anio'] = $campos['anio'] + 1;

            $campos['numero'] = $this->proximoOrden();
            $pago = PagoMovimiento::create($campos);

            ///////////adjuntar boleta de anticipo a documentos
            $objAnticipo = new AnticipoController();
            $objCaja = new CajaController();
            $objAnticipo->generarBoletaAnticipo($anticipoId);

            $formularioLiquidacion = FormularioLiquidacion::findOrFail($anticipo->formulario_liquidacion_id);
            $res = $objCaja->subirDocumento($formularioLiquidacion);
            DocumentoCompra::whereFormularioLiquidacionId($anticipo->formulario_liquidacion_id)->whereDescripcion(\App\Patrones\DocumentoCompra::Anticipos)
                ->update(['agregado' => true]);

            if (is_null($formularioLiquidacion->url_documento)) {
                $formularioLiquidacion->url_documento = $formularioLiquidacion->id . '_document.pdf';
                $formularioLiquidacion->save();
            }

            ///////////

//            PagoMovimiento::where('id', $pago->id)->update(['saldo_caja' => $pago->saldo_pago, 'saldo_banco' => $pago->saldo_pago_banco]);

            \DB::commit();
            Flash::success('Anticipo pagado correctamente.');

            echo "<script>
            window.location.href = '/pagos/anticipos';
            window.open('/anticipos/'+'$anticipoId'+'/imprimir', '_blank');
                </script>";
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function getDevoluciones(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 3 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $esCancelado = $request->txtEstado;

        if (is_null($esCancelado)) {
            $esCancelado = false;
        }
        if ($esCancelado) {
            $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
            $devoluciones = PagoMovimiento::
            join('bono', 'bono.id', '=', 'pago_movimiento.origen_id')
                ->join('formulario_liquidacion', 'formulario_liquidacion.id', '=', 'bono.formulario_liquidacion_id')
                ->join('cliente', 'cliente.id', '=', 'formulario_liquidacion.cliente_id')
                ->join('cooperativa', 'cooperativa.id', '=', 'cliente.cooperativa_id')
                ->where('bono.es_cancelado', true)
                ->where('origen_type', Bono::class)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where('producto', 'ilike', "%{$txtBuscar}%")
                        ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                        ->orWhere('cliente.nombre', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('cliente.nit', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('razon_social', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('pago_movimiento.codigo', 'ilike', "%{$txtBuscar}%");
                })
                ->whereBetween('pago_movimiento.created_at', [$fecha_inicial, $fecha_final])
                ->orderByDesc('pago_movimiento.id')
                ->select('pago_movimiento.*', 'cliente.nombre', 'cliente.nit', 'bono.id',
                    'cooperativa.razon_social', 'formulario_liquidacion.producto', 'bono.formulario_liquidacion_id',
                    DB::raw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4))) as lote"))
                ->paginate();
        } else {
            $devoluciones = Bono::
            where('es_cancelado', false)
                ->whereHas('formularioLiquidacion', function ($query) {
                    $query->where('estado', Estado::Anulado);
                })
                ->where(function ($q) use ($txtBuscar) {
                    $q->whereHas('formularioLiquidacion', function ($q) use ($txtBuscar) {
                        $q->where('producto', 'ilike', "%{$txtBuscar}%")
                            ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                            ->orWhereHas('cliente', function ($q) use ($txtBuscar) {
                                $q->where('nombre', 'ilike', "%{$txtBuscar}%")
                                    ->orWhere('nit', 'ilike', "%{$txtBuscar}%")
                                    ->orWhereHas('cooperativa', function ($q) use ($txtBuscar) {
                                        $q->where('razon_social', 'ilike', "%{$txtBuscar}%");
                                    });
                            });
                    });
                })
                ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                ->orderByDesc('fecha')
                ->paginate();

        }
        return view('bonos.caja_devoluciones')->with('devoluciones', $devoluciones)->with('esCancelado', $esCancelado);
    }

    public
    function registrarDevolucion(request $request)
    {
        $bonoId = $request->idBono;
        $devolucion = Bono::find($bonoId);
        if ($devolucion->es_cancelado) {
            Flash::error('La devolución ya fue pagada anteriormente.');
            return redirect(route('pagos.devoluciones'));
        }
        Bono::where('id', $bonoId)->update(['es_cancelado' => true]);

        $campos['codigo'] = $this->getCodigo('Ingreso');

        $campos['monto'] = $devolucion->monto;
        if ($request->metodo == TipoPago::CuentaBancaria) {
            $campos['banco'] = $request->banco;
            $campos['glosa'] = 'Liquidación por devolución de Lote ' . $devolucion->formularioLiquidacion->lote . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
        } else
            $campos['glosa'] = 'Liquidación por devolución de Lote ' . $devolucion->formularioLiquidacion->lote;
        $campos['origen_type'] = Bono::class;
        $campos['origen_id'] = $devolucion->id;
        $campos['metodo'] = $request->metodo;
        $campos['anio'] = date('y');
        if (date('m') >= 10)
            $campos['anio'] = $campos['anio'] + 1;

        $campos['numero'] = $this->proximoOrden();
        $pago = PagoMovimiento::create($campos);
//        PagoMovimiento::where('id', $pago->id)->update(['saldo_caja' => $pago->saldo_pago, 'saldo_banco' => $pago->saldo_pago_banco]);

        Flash::success('Devolución pagada correctamente.');
        echo "<script>
            window.location.href = '/pagos-devoluciones';
            window.open('/bonos/'+'$bonoId'+'/imprimir', '_blank');
                </script>";

    }

    public
    function registrarFactura(Request $request)
    {
        $movimiento = \DB::table('movimiento')->where('id', $request->idMovimiento);

        if (empty($movimiento)) {
            Flash::error('Movimiento no encontrado');
            return redirect(route('movimientos.index'));
        }

        $movimiento->update(['factura' => $request->factura]);

        Flash::success('Factura registrada correctamente.');
        return redirect(route('movimientos.index'));
    }


    public function anular(Request $request)
    {
        $id = $request->idPago;
        \DB::beginTransaction();
        try {
            $pago = PagoMovimiento::find($id);
            $pago->alta = false;
            $pago->motivo_anulacion = $request->motivo_anulacion;
            $pago->save();

            switch ($pago->origen_type) {
                case Movimiento::class:
                    $mov = Movimiento::find($pago->origen_id);
                    $mov->update(['es_cancelado' => false]);
                    if ($mov->origen_type == Venta::class) {
                        $costoVenta = CostoVenta::
                        whereVentaId($mov->origen_id)
                            ->where('monto', $mov->monto)
                            ->whereDescripcion(strstr($mov->motivo, ' EN LOTE CM', true))
                            ->first();
                        if ($costoVenta)
                            $costoVenta->delete($costoVenta->id);
                    }
                    break;
                case FormularioLiquidacion::class:
                    FormularioLiquidacion::whereId($pago->origen_id)->update(['es_cancelado' => false]);
                    $formularioLiquidacion=FormularioLiquidacion::find($pago->origen_id);
                    $obj = new RetencionPagoController();
                    $obj->restar($formularioLiquidacion);
                    break;
                case CuentaCobrar::class:
                    CuentaCobrar::whereId($pago->origen_id)->update(['es_cancelado' => false]);
                    break;
                case Bono::class:
                    Bono::whereId($pago->origen_id)->update(['es_cancelado' => false]);
                    break;
                case Anticipo::class:
                    Anticipo::whereId($pago->origen_id)->update(['es_cancelado' => false]);
                    break;
                case Prestamo::class:
                    Prestamo::whereId($pago->origen_id)->update(['es_cancelado' => false]);
                    $cuenta = CuentaCobrar::wherePrestamoId($pago->origen_id)->first();

                    if ($cuenta->origen_type == FormularioLiquidacion::class) {
                        $form = FormularioLiquidacion::find($cuenta->origen_id);
                        if ($form->estado != Estado::EnProceso and $form->estado != Estado::Anulado) {
                            \DB::rollBack();
                            Flash::error('No se puede anular, porque la cuenta por cobrar generada se encuentra en un lote liquidado');
                            return redirect(route('movimientos.lista-pagos'));
                        }
                    }
                    $cuenta->delete($cuenta->id);
                    break;
                case PagoRetencion::class:
                    PagoRetencion::whereId($pago->origen_id)->update(['es_cancelado' => false]);
                    break;
                case Venta::class:
                    Venta::whereId($pago->origen_id)->update(['es_cancelado' => false]);
                    break;
            }

            $creacionDeAnulado = date('Y-m-d', strtotime($pago->created_at));
            $this->actualizarSaldosPorAnulacion($creacionDeAnulado);

            \DB::commit();
            Flash::success('Pago anulado correctamente.');
            return redirect(route('movimientos.lista-pagos'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function listaPagos(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $pagos = PagoMovimiento::
        where('created_at', '>=', DB::raw("NOW() - INTERVAL '200 HOURS'"))
            ->whereAlta(true)
            ->where(function ($q) use ($txtBuscar) {
                $q->where('codigo', 'ilike', "%{$txtBuscar}%")
                    ->orWhere('monto', 'ilike', "%{$txtBuscar}%")
                    ->orWhere('glosa', 'ilike', "%{$txtBuscar}%");
            })
            ->orderByDesc('created_at')->orderBy('id')
            ->paginate();

        return view('movimientos.pagos_anular', compact('pagos'));
    }

    public function getSaldo($fecha, $metodo, $banco = null)
    {
        $diario = SaldoDiario::where(DB::raw("DATE(created_at)"), $fecha)->first();
        if (is_null($diario))
            $diario = SaldoDiario::where(DB::raw("DATE(created_at)"), '>', $fecha)->orderBy('created_at')->first();

        if ($metodo == TipoPago::Efectivo)
            return $diario->monto_caja;
        else {
            if ($banco == Banco::BNB)
                return $diario->monto_bnb;
            else
                return $diario->monto_economico;
        }

    }

    public function getSaldoDolares($fecha)
    {
        $diario = SaldoDiario::where(DB::raw("DATE(created_at)"), $fecha)->first();
        if (is_null($diario))
            $diario = SaldoDiario::where(DB::raw("DATE(created_at)"), '>', $fecha)->orderBy('created_at')->first();

        return $diario->monto_dolares;
    }

    public function actualizarSaldoInicial()
    {
        $hoy = date('Y-m-d');
        $contador = SaldoDiario::where(DB::raw("DATE(created_at)"), $hoy)->count();

        if ($contador == 0) {
            $pago = PagoMovimiento::where(DB::raw("DATE(created_at)"), '<', $hoy)->whereAlta(true)->orderByDesc('created_at')->first();
            $pagoDolar = PagoDolar::where(DB::raw("DATE(created_at)"), '<', $hoy)->orderByDesc('created_at')->first();

//            $campos['monto_banco'] = $pago->saldo_banco;
            $campos['monto_caja'] = $pago->saldo_caja;
            $campos['monto_bnb'] = $pago->saldo_bnb;
            $campos['monto_economico'] = $pago->saldo_economico;
            $campos['monto_dolares'] = $pagoDolar->saldo;
            SaldoDiario::create($campos);
        }
    }

    private function actualizarSaldosPorAnulacion($creacionDeAnulado)
    {
        $saldos = SaldoDiario::where(DB::raw("DATE(created_at)"), '>', $creacionDeAnulado)->orderByDesc('created_at')->get();

        foreach ($saldos as $saldo) {
            $fecha = date('Y-m-d', strtotime($saldo->created_at));
            $pago = PagoMovimiento::where(DB::raw("DATE(created_at)"), '<', $fecha)
                ->whereAlta(true)->orderByDesc('created_at')->first();

            SaldoDiario::where(DB::raw("DATE(created_at)"), $fecha)->update([
                //'monto_banco' => $pago->saldo_banco,
                'monto_caja' => $pago->saldo_caja, 'monto_bnb' => $pago->saldo_bnb, 'monto_economico' => $pago->saldo_economico]);
        }
    }

    public function getSaldosCaja($fecha)
    {
        $caja = PagoMovimiento::where('created_at', '<', $fecha)->whereAlta(true)->whereMetodo(TipoPago::Efectivo)
            ->get()->sum('monto_signo');


        $bnb = PagoMovimiento::where('created_at', '<', $fecha)->whereAlta(true)->whereMetodo(TipoPago::CuentaBancaria)->whereBanco(Banco::BNB)
            ->get()->sum('monto_signo');
        $economico = PagoMovimiento::where('created_at', '<', $fecha)->whereAlta(true)->whereMetodo(TipoPago::CuentaBancaria)
            ->where(function ($q) {
                $q->whereBanco(Banco::Economico)->orWhere('banco', 'Fortaleza');
            })
            ->get()->sum('monto_signo');

        $dolar = PagoDolar::where('created_at', '<', $fecha)->where('created_at', '>', '2023-04-07')->get()->sum('monto_signo');


        return response()->json(['caja' => $caja, 'bnb' => $bnb, 'economico' => $economico, 'dolares' => $dolar]);

    }

    public function actualizarSaldoBnb()
    {
        $saldos = SaldoDiario::get();
        try {
            foreach ($saldos as $saldo) {
                \DB::table('saldo_diario')->
                where('id', $saldo->id)->update([
                    'monto_bnb' => $saldo->monto_banco, 'monto_economico' => 0]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function show($id)
    {
        $movimiento = Movimiento::whereId($id)->whereEsAprobado(false)->first();

        if (empty($movimiento)) {
            Flash::error('Movimiento no encontrado');

            return redirect(route('home'));
        }
        return view('movimientos.aprobar')->with('movimiento', $movimiento);
    }

    public function aprobar(Request $request)
    {
        $id = $request->id;
        $movimiento = Movimiento::whereId($id)->whereEsAprobado(false)->first();

        if (empty($movimiento)) {
            Flash::error('Movimiento no encontrado');

            return redirect(route('home'));
        }
        $movimiento->update(['es_aprobado' => true]);
        Flash::success('Movimiento aprobado');

        return redirect(route('home'));
    }

    public function rechazar($id)
    {
        $movimiento = Movimiento::whereId($id)->whereEsAprobado(false)->first();

        if (empty($movimiento)) {
            Flash::error('Movimiento no encontrado');

            return redirect(route('home'));
        }
        $movimiento->delete($id);
        Flash::success('Movimiento rechazado');
        return redirect(route('home'));
    }

    public function getCatalogo($tipo)
    {
        $movimientos = MovimientoCatalogo::whereTipo($tipo)->orderBy('descripcion')->get()->pluck('descripcion', 'info');
        return json_encode($movimientos);
    }

    public function getLotes($tipo)
    {
        if ($tipo == 'Compra') {
            $lotes = FormularioLiquidacion::where('estado', Estado::EnProceso)
                ->orWhere(function ($q) {
                    $q->where('estado', Estado::Liquidado)
                        ->where('fecha_liquidacion', '>=', DB::raw("NOW() - INTERVAL '15 DAYS'"));
                })
                ->orderByDesc('id')->get()->pluck('lote', 'id')->toArray();
        } else {
            $lotes = Venta::where('estado', EstadoVenta::EnProceso)
                ->orWhere(function ($q) {
                    $q->where('estado', EstadoVenta::Liquidado)
                        ->where('fecha_venta', '>=', DB::raw("NOW() - INTERVAL '30 DAYS'"));
                })
                ->orderByDesc('id')->get()->pluck('lote', 'id')->toArray();
        }

        return json_encode($lotes);
    }

    public function proximoOrden()
    {
        $max = \DB::table('pago_movimiento')->max('numero');
        return ($max + 1);
    }
}
