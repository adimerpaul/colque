<?php

namespace App\Http\Controllers;

use App\Models\DescuentoBonificacion;
use App\Models\FormularioDescuento;
use App\Models\FormularioKardex;
use App\Models\FormularioLiquidacion;
use App\Models\PagoMovimiento;
use App\Models\PagoRetencion;
use App\Models\Retencion;
use App\Patrones\ClaseDescuento;
use App\Patrones\Estado;
use App\Patrones\Fachada;
use App\Patrones\TipoDescuentoBonificacion;
use App\Patrones\TipoPago;
use Illuminate\Http\Request;
use DB;
use Flash;
use Luecano\NumeroALetras\NumeroALetras;


class RetencionPagoController
{

    public function index(Request $request)
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
        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        if ($esCancelado) {
            $retenciones = PagoMovimiento::

            where(function ($q) use ($txtBuscar) {
                $q->where('glosa', 'ilike', "%{$txtBuscar}%")
                    ->orWhere('codigo', 'ilike', "%{$txtBuscar}%");
            })
                ->whereOrigenType(PagoRetencion::class)
                ->whereAlta(true)
                ->whereBetween('created_at', [$fecha_inicial, $fecha_final])
                ->orderByDesc('id')
                ->paginate();
        } else {
            $retenciones = PagoRetencion::
            where('es_cancelado', false)
                ->whereEsAprobado(true)
                ->where('motivo', 'ilike', "%{$txtBuscar}%")
                ->whereBetween('created_at', [$fecha_inicial, $fecha_final])
                ->orderByDesc('id')
                ->paginate();
        }


        return view('retenciones_pagos.retenciones')->with('retenciones', $retenciones)->with('esCancelado', $esCancelado);
    }


    public function registrar($formulario)
    {
        $noPagables = Fachada::getDescuentosNoPagables();
        $idCooperativa = $formulario->cliente->cooperativa_id;
        $fechaInferior = $this->getFechasQuincena()['inicio'];
        $fechaSuperior = $this->getFechasQuincena()['fin'];
        $descuentosFormulario = FormularioDescuento::whereFormularioLiquidacionId($formulario->id)
            ->whereHas('descuentoBonificacion', function ($q) use ($noPagables) {
                $q->where('tipo', TipoDescuentoBonificacion::Retencion)
                    ->orWhere('tipo', TipoDescuentoBonificacion::Descuento)
                    ->orWhere(function ($q)  {
                        $q->where('tipo', TipoDescuentoBonificacion::Bonificacion)
                            ->where('clase', ClaseDescuento::Acumulativo);
                    })
                //    ->whereNotIn('nombre', $noPagables)
                ;
            })
            ->get();

        foreach ($descuentosFormulario as $descuento) {
            $retencion = Retencion::whereCooperativaId($idCooperativa)
                ->where('created_at', '>=', $fechaInferior)->where('created_at', '<=', $fechaSuperior)
                ->whereNombre($descuento->descuentoBonificacion->nombre)
                ->first();

            if ($retencion) {
                $retencion->monto = $retencion->monto + $descuento->sub_total;
                $retencion->save();
            } else {
                $nombre='Pago de retención ';
                if($descuento->descuentoBonificacion->tipo==TipoDescuentoBonificacion::Bonificacion)
                    $nombre='Pago de bonificación ';

                $campos['monto'] = $descuento->sub_total;
                $campos['motivo'] = $nombre . $descuento->descuentoBonificacion->nombre . ' de cooperativa ' . $formulario->cliente->cooperativa->razon_social . '  en ' . $this->getFechasQuincena()['quincena'];
                $campos['nombre'] = $descuento->descuentoBonificacion->nombre;
                $campos['cooperativa_id'] = $idCooperativa;
                Retencion::create($campos);
            }
        }
    }

    public function restar($formulario)
    {
        $noPagables = Fachada::getDescuentosNoPagables();
        $idCooperativa = $formulario->cliente->cooperativa_id;
        $fecha = date('Y-m-d', strtotime($formulario->fecha_hora_liquidacion));
        $fechaInferior = $this->getFechasQuincena($fecha)['inicio'];
        $fechaSuperior = $this->getFechasQuincena($fecha)['fin'];
        $descuentosFormulario = FormularioDescuento::whereFormularioLiquidacionId($formulario->id)
            ->whereHas('descuentoBonificacion', function ($q) use ($noPagables) {
                $q->where('tipo', TipoDescuentoBonificacion::Retencion)
                    ->orWhere('tipo', TipoDescuentoBonificacion::Descuento)
                    ->orWhere(function ($q)  {
                        $q->where('tipo', TipoDescuentoBonificacion::Bonificacion)
                            ->where('clase', ClaseDescuento::Acumulativo);
                    })
                //    ->whereNotIn('nombre', $noPagables)
                ;
            })->get();

        foreach ($descuentosFormulario as $descuento) {
            $retencion = Retencion::whereCooperativaId($idCooperativa)
                ->where('created_at', '>=', $fechaInferior)->where('created_at', '<=', $fechaSuperior)
                ->whereNombre($descuento->descuentoBonificacion->nombre)
                ->first();

            if ($retencion) {
                $retencion->monto = $retencion->monto - $descuento->sub_total;
                $retencion->save();
            }
        }
    }

    private function getFechasQuincena($fecha = null)
    {
        if (is_null($fecha))
            $fecha = date('Y-m-d');

        if (date('d') > 15) {
            $fechaInicio = date("Y-m-16 00:00:00", strtotime($fecha));
            $fechaFin = date("Y-m-t 23:59:59", strtotime($fecha));
            $numeroQuincena = '2da quincena';
        } else {
            $fechaInicio = date("Y-m-01 00:00:00", strtotime($fecha));
            $fechaFin = date("Y-m-15 23:59:59", strtotime($fecha));
            $numeroQuincena = '1ra quincena';
        }
        //setlocale(LC_TIME, "spanish");
        $mes = Fachada::getMesEspanol(date('n')); //strftime("%B");
        return (['inicio' => $fechaInicio, 'fin' => $fechaFin, 'quincena' => $numeroQuincena . ' de ' . $mes]);
    }

    public function registrarPago(Request $request)
    {
        $retencionId = $request->idRetencion;
        $retencion = PagoRetencion::find($retencionId);
        if ($retencion->es_cancelado) {
            Flash::error('La retención ya fue pagada anteriormente.');
            return redirect(route('retenciones-pagos.index'));
        }
        PagoRetencion::where('id', $retencionId)->update(['es_cancelado' => true]);
        $obj = new MovimientoController();

        $campos['monto'] = $retencion->monto;
        $campos['origen_type'] = PagoRetencion::class;
        $campos['origen_id'] = $retencionId;
        $campos['metodo'] = $request->metodo;
        $campos['codigo'] = $obj->getCodigo('Egreso');
        if ($request->metodo == TipoPago::CuentaBancaria) {
            $campos['banco'] = $request->banco;
            $campos['glosa'] = $retencion->motivo . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
        } else
            $campos['glosa'] = $retencion->motivo;
        $campos['anio'] = date('y');
        if (date('m') >= 10)
            $campos['anio'] = $campos['anio'] + 1;

        $objMov = new MovimientoController();
        $campos['numero'] = $objMov->proximoOrden();
        $pago = PagoMovimiento::create($campos);
//        PagoMovimiento::where('id', $pago->id)->update(['saldo_caja' => $pago->saldo_pago, 'saldo_banco' => $pago->saldo_pago_banco]);

        Flash::success('Retención pagada correctamente.');

        echo "<script>
            window.location.href = '/retenciones-pagos';
            window.open('/retenciones-pagos/'+'$retencionId'+'/imprimir', '_blank');
            window.open('/retenciones-detalle-pdf/'+'$retencionId', '_blank');
                </script>";

    }

    public function imprimir($retencionId)
    {
        $retencion = PagoMovimiento::whereOrigenType(PagoRetencion::class)->whereOrigenId($retencionId)->orderByDesc('id')->first();
        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($retencion->monto, 2, 'BOLIVIANOS', 'CENTAVOS');


        $vistaurl = "retenciones_pagos.imprimir";
        $view = \View::make($vistaurl, compact('retencion', 'literal'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboRetencion-' . $retencion->codigo_caja . '.pdf');
    }

    public function getDetalle($id)
    {
        $retencionPago = Retencion::find($id);
        $cooperativaId = $retencionPago->cooperativa_id;
        $formularios = FormularioLiquidacion::
        whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])
            ->where(function ($q) use ($cooperativaId) {
                $q->whereHas('cliente', function ($q) use ($cooperativaId) {
                    $q->where('cooperativa_id', $cooperativaId);
                });
            })
            ->whereBetween('fecha_hora_liquidacion', [$retencionPago->fecha_hora_inicio, $retencionPago->fecha_hora_fin])
            ->get();

        $retenciones = DescuentoBonificacion::whereCooperativaId($cooperativaId)
            ->where('tipo', TipoDescuentoBonificacion::Retencion)->whereAlta(true)->get();
        $retencionesTotales = [];


        $i = 0;
        foreach ($retenciones as $retencion) {
            $i = $i + 1;
            ${"retencion" . $i} = 0;
            foreach ($formularios as $formulario) {
                ${"retencion" . $i} = ${"retencion" . $i} + $formulario->retenciones_cooperativa[$retencion->nombre];
            }
            $retencionesTotales += array($retencion->nombre => ${"retencion" . $i});
        }
        $nroRetenciones = $retenciones->count();

        $descuentos = DescuentoBonificacion::whereCooperativaId($cooperativaId)->where('tipo', TipoDescuentoBonificacion::Descuento)->whereAlta(true)->get();
        $descuentosTotales = [];

        $j = 0;
        foreach ($descuentos as $descuento) {
            $j = $j + 1;
            ${"descuento" . $j} = 0;
            foreach ($formularios as $formulario) {
                ${"descuento" . $j} = ${"descuento" . $j} + $formulario->descuentos_cooperativa[$descuento->nombre];
            }
            $descuentosTotales += array($descuento->nombre => ${"descuento" . $j});
        }
        $nroDescuentos = $descuentos->count();


        $bonificaciones = DescuentoBonificacion::whereCooperativaId($cooperativaId)
            ->where('tipo', TipoDescuentoBonificacion::Bonificacion)
            ->whereClase(ClaseDescuento::Acumulativo)
            ->whereAlta(true)->get();
        $bonificacionesTotales = [];

        $j = 0;
        foreach ($bonificaciones as $bonificacion) {
            $j = $j + 1;
            ${"descuento" . $j} = 0;
            foreach ($formularios as $formulario) {
                ${"descuento" . $j} = ${"descuento" . $j} + $formulario->bonificaciones_cooperativa[$bonificacion->nombre];
            }
            $bonificacionesTotales += array($bonificacion->nombre => ${"descuento" . $j});
        }
        $nroBonificaciones = $bonificaciones->count();


        return view('retenciones_pagos.detalle', compact('formularios', 'descuentos', 'retenciones',
            'bonificaciones', 'nroBonificaciones', 'bonificacionesTotales',
            'nroRetenciones', 'nroDescuentos', 'retencionesTotales', 'descuentosTotales', 'retencionPago'));
    }

    public function getRetenciones($productorId, Request $request)
    {
        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 12 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $retenciones = Retencion::
        where('es_aprobado', false)
            ->whereBetween('retencion.created_at', [$fecha_inicial, $fecha_final])
            ->whereCooperativaId($productorId)
            ->orderByDesc('id')
            ->paginate(50);


        return view('retenciones_pagos.lista_calculos')->with('retenciones', $retenciones)->with('productorId', $productorId);
    }

    public function aprobar(Request $request)
    {
        $input = $request->all();
        \DB::beginTransaction();
        try {
            $seleccionados = explode(",", $request->seleccionados);
            if (is_null($request->seleccionados)) {
                Flash::error('Retenciones no encontradas');

                return redirect(route('cooperativas.index'));
            }
            $contadorSeleccionados = count($seleccionados);

            //formularios seleccionados
            $retenciones = Retencion::whereIn('id', $seleccionados)->whereEsAprobado(false)->get();


            $contadorRentenciones = $retenciones->count();

            if ($contadorRentenciones == 0) {
                Flash::error('Retenciones no encontradas');
                return redirect(route('cooperativas.index'));
            }
            if ($contadorSeleccionados != $contadorRentenciones) {
                Flash::error('No se aprobaron las retenciones, porque algunas ya se encontraban aprobadas anteriormente');
                return redirect(route('cooperativas.index'));
            }


            Retencion::whereIn('id', $seleccionados)->update(['es_aprobado' => true]);

            $input['retenciones_id'] = $request->seleccionados;
            $pago = PagoRetencion::create($input);

            \DB::commit();

            Flash::success('Retenciones aprobadas correctamente.');

            return redirect(route('retenciones.lista', ['productorId' => $pago->cooperativa_id]));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function getRetencionesIncluidas($id)
    {
        $pago = PagoRetencion::find($id);
        $seleccionados = explode(",", $pago->retenciones_id);
        $retenciones = Retencion::whereIn('id', $seleccionados)->paginate(50);
        return view('retenciones_pagos.lista_incluidas')->with('retenciones', $retenciones)->with('pago', $pago);

    }

    public function getDetallePdf($id)
    {
        $retencionPago = PagoRetencion::find($id);
        $seleccionados = explode(",", $retencionPago->retenciones_id);
        $retencionesPagos = Retencion::whereIn('id', $seleccionados)->orderBy('created_at')->get();

        $cooperativaId = $retencionesPagos[0]->cooperativa_id;
        $retPagoUltimo = Retencion::whereIn('id', $seleccionados)->orderByDesc('created_at')->first();


        $formularios = FormularioLiquidacion::
        whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])
            ->where(function ($q) use ($cooperativaId) {
                $q->whereHas('cliente', function ($q) use ($cooperativaId) {
                    $q->where('cooperativa_id', $cooperativaId);
                });
            })
            ->whereBetween('fecha_hora_liquidacion', [$retencionesPagos[0]->fecha_hora_inicio, $retPagoUltimo->fecha_hora_fin])
            ->orderby('created_at')
            ->get();

        if ($cooperativaId == 28) //cooperativa colquechaca
        {
            $retenciones = DescuentoBonificacion::whereCooperativaId($cooperativaId)
                ->whereNombre($retPagoUltimo->nombre)
                ->where('tipo', TipoDescuentoBonificacion::Retencion)->whereAlta(true)->get();
        } else {
            $retenciones = DescuentoBonificacion::whereCooperativaId($cooperativaId)
                ->where('tipo', TipoDescuentoBonificacion::Retencion)->whereAlta(true)->get();
        }

        //  dd($retPagoUltimo->nombre);
        $retencionesTotales = [];


        $i = 0;
        foreach ($retenciones as $retencion) {
            $i = $i + 1;
            ${"retencion" . $i} = 0;
            foreach ($formularios as $formulario) {
                ${"retencion" . $i} = ${"retencion" . $i} + $formulario->retenciones_cooperativa[$retencion->nombre];
            }
            $retencionesTotales += array($retencion->nombre => ${"retencion" . $i});
        }
        $nroRetenciones = $retenciones->count();
        if ($cooperativaId == 28) //cooperativa colquechaca
        {
            $descuentos = DescuentoBonificacion::whereCooperativaId($cooperativaId)
                ->whereNombre($retPagoUltimo->nombre)
                ->where('tipo', TipoDescuentoBonificacion::Descuento)->whereAlta(true)->get();
        } else {
            $descuentos = DescuentoBonificacion::whereCooperativaId($cooperativaId)
                ->where('tipo', TipoDescuentoBonificacion::Descuento)->whereAlta(true)->get();
        }
        $descuentosTotales = [];

        $j = 0;
        foreach ($descuentos as $descuento) {
            $j = $j + 1;
            ${"descuento" . $j} = 0;
            foreach ($formularios as $formulario) {
                ${"descuento" . $j} = ${"descuento" . $j} + $formulario->descuentos_cooperativa[$descuento->nombre];
            }
            $descuentosTotales += array($descuento->nombre => ${"descuento" . $j});
        }
        $nroDescuentos = $descuentos->count();

        $vistaurl = "retenciones_pagos.detalle_pdf";
        $view = \View::make($vistaurl, compact('formularios', 'descuentos', 'retenciones',
            'nroRetenciones', 'nroDescuentos', 'retencionesTotales', 'descuentosTotales', 'retencionPago', 'retPagoUltimo'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'landscape');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(35, 570, 'www.colquechaca.com', null, 8, array(0, 0, 0));

        $fechaImpresion = date('d/m/Y H:i');

        $canvas->page_text(645, 570, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . $fechaImpresion, null, 6, array(0, 0, 0));


        return $pdf->stream('DetalleRetencion.pdf');
    }

    public function eliminar($id)
    {
        $retencion = Retencion::find($id);
        $ret = $retencion->cooperativa_id;
        $retencion->delete($id);


        Flash::success('Retencion eliminada');

        return redirect(route('retenciones.lista', $ret));
    }

    public function aprobarACaja(Request $request)
    {
        $id = $request->id;
        $retencion = PagoRetencion::whereId($id)->whereEsAprobado(false)->first();

        if (empty($retencion)) {
            Flash::error('Retención no encontrada');

            return redirect(route('retenciones-pagos.index'));
        }
        $retencion->update(['es_aprobado' => true]);
        Flash::success('Retención aprobada');

        return redirect(route('retenciones-pagos.index'));
    }
}
