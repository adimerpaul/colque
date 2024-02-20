<?php

namespace App\Http\Controllers;

use App\Models\Costo;
use App\Models\CostoVenta;
use App\Models\FormularioLiquidacion;
use App\Models\Movimiento;
use App\Models\PagoMovimiento;
use App\Models\Venta;
use App\Patrones\TipoPago;
use Illuminate\Http\Request;
use Flash;
use Luecano\NumeroALetras\NumeroALetras;
use DB;

class PagoLaboratorioPesajeController extends AppBaseController
{
    public function storeLaboratorio(Request $request)
    {
        if (is_null($request->lotes) and $request->tipoLoteLab == 'Compra') {
            Flash::error('ERROR!!! Elija los lotes de la lista desplegable');
            return redirect(route('movimientos.create'));
        }
        if (is_null($request->lotesVentas) and $request->tipoLoteLab == 'Venta') {
            Flash::error('ERROR!!! Elija los lotes de la lista desplegable');
            return redirect(route('movimientos.create'));
        }
        $movs = array();
        \DB::beginTransaction();
        try {
            if (auth()->user()->personal->es_jefe) {
                $input = $request->all();
                $input['user_id'] = auth()->user()->id;

                $input['tipo'] = 'Egreso';
                $input['es_aprobado'] = true;
                $input['es_cancelado'] = true;
                $input['proveedor_id'] = $request->laboratorio_id;

                if ($request->tipoLoteLab == 'Compra') {
                    //compras
                    $input['origen_type'] = FormularioLiquidacion::class;
                    $movs = $this->registrarLabCompra($request, $movs, $input);
                } else {
                    $input['origen_type'] = Venta::class;
                    $movs = $this->registrarLabVenta($request, $movs, $input);
                }

                \DB::commit();
                Flash::success('Pago de laboratorio realizado correctamente.');
//recibo
                $movs = implode(",", $movs);

                echo "<script>
                    window.location.href = '/movimientos';
                    window.open('/comprobante-laboratorio/'+ '$movs', '_blank');
                </script>";
//

            } else {
                \DB::rollBack();
                Flash::error('No tiene los permisos para realizar esta acción.');
                return redirect(route('movimientos.create'));
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function storePesaje(Request $request)
    {
        if (is_null($request->lotes) and $request->tipoLotePesaje == 'Compra') {
            Flash::error('ERROR!!! Elija los lotes de la lista desplegable');
            return redirect(route('movimientos.create'));
        }
        if (is_null($request->lotesVentas) and $request->tipoLotePesaje == 'Venta') {
            Flash::error('ERROR!!! Elija los lotes de la lista desplegable');
            return redirect(route('movimientos.create'));
        }
        $movs = array();
        \DB::beginTransaction();
        try {
            $input = $request->all();
            $input['user_id'] = auth()->user()->id;
            $input['tipo'] = 'Egreso';
            $input['es_aprobado'] = true;
            $input['es_cancelado'] = true;
            $input['proveedor_id'] = $request->proveedor_pesaje;

            if ($request->tipoLotePesaje == 'Compra') {
                //compras
                $input['origen_type'] = FormularioLiquidacion::class;
                $movs = $this->registrarPesajeCompra($request, $movs, $input);
            } else {
                $input['origen_type'] = Venta::class;
                $movs = $this->registrarPesajeVenta($request, $movs, $input);
            }


            \DB::commit();
            Flash::success('Pago de pesaje realizado correctamente.');
//recibo
            $movs = implode(",", $movs);

            echo "<script>
                    window.location.href = '/movimientos';
                    window.open('/comprobante-pesaje/'+ '$movs', '_blank');
                </script>";
//

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function registrarLabCompra($request, $movs, $input)
    {

        foreach ($request->lotes as $formId) {
            $form = FormularioLiquidacion::find($formId);
            $obj = new CostoController();

            $input['origen_id'] = $formId;
            $input['motivo'] = trim(sprintf("%s %s", 'ANÁLISIS QUÍMICO EN LOTE', $form->lote));
            if(isset($request->observacion))
                $input['motivo'] = trim(sprintf("%s %s. %s", 'ANÁLISIS QUÍMICO EN LOTE', $form->lote, $request->observacion));
            switch ($form->letra) {
                case 'A':
                    $monto = $request->montoA;
                    break;
                case 'B':
                    $monto = $request->montoB;
                    break;
                case 'C':
                    $monto = $request->montoC;
                    break;
                case 'D':
                    $monto = $request->montoD;
                    break;
                case 'E':
                    $monto = $request->montoE;
                    break;
                case 'F':
                    $monto = $request->montoF;
                    break;
                case 'G':
                    $monto = $request->montoG;
                    break;
            }
            $obj->actualizarLaboratorio('laboratorio', $formId, $monto);

            $input['monto'] = $monto;
            $movimiento = Movimiento::create($input);

            array_push($movs, $movimiento->id);

            $this->registrarPago($movimiento, $request);
        }
        return $movs;
    }

    public function registrarLabVenta($request, $movs, $input)
    {

        foreach ($request->lotesVentas as $ventaId) {
            $venta = Venta::find($ventaId);
            $obj = new CostoVentaController();

            $input['origen_id'] = $ventaId;
            $input['motivo'] = trim(sprintf("%s %s", 'ANÁLISIS QUÍMICO EN LOTE', $venta->lote));
            if(isset($request->observacion))
                $input['motivo'] = trim(sprintf("%s %s. %s", 'ANÁLISIS QUÍMICO EN LOTE', $venta->lote, $request->observacion));
            switch ($venta->letra) {
                case 'A':
                    $monto = $request->montoA;
                    break;
                case 'B':
                    $monto = $request->montoB;
                    break;
                case 'C':
                    $monto = $request->montoC;
                    break;
                case 'D':
                    $monto = $request->montoD;
                    break;
                case 'E':
                    $monto = $request->montoE;
                    break;
                case 'F':
                    $monto = $request->montoF;
                    break;
                case 'G':
                    $monto = $request->montoG;
                    break;
            }
            $obj->registrarLaboratorio($ventaId, $monto);

            $input['monto'] = $monto;
            $movimiento = Movimiento::create($input);

            array_push($movs, $movimiento->id);

            $this->registrarPago($movimiento, $request);
        }
        return $movs;
    }


    public function registrarPesajeCompra($request, $movs, $input)
    {

        foreach ($request->lotes as $formId) {
            $form = FormularioLiquidacion::find($formId);
            $obj = new CostoController();

            $input['origen_id'] = $formId;
            $input['motivo'] = trim(sprintf("%s %s", 'GASTOS DE PESAJE DE MINERAL EN LOTE', $form->lote));
            switch ($form->letra) {
                case 'A':
                    $monto = $request->montoA;
                    break;
                case 'B':
                    $monto = $request->montoB;
                    break;
                case 'C':
                    $monto = $request->montoC;
                    break;
                case 'D':
                    $monto = $request->montoD;
                    break;
                case 'E':
                    $monto = $request->montoE;
                    break;
                case 'F':
                    $monto = $request->montoF;
                    break;
                case 'G':
                    $monto = $request->montoG;
                    break;
            }
            $costo = Costo::whereFormularioLiquidacionId($formId)->first();
            $costo->update(array('pesaje' => $monto));
            $costo->save();


            $input['monto'] = $monto;
            $movimiento = Movimiento::create($input);

            array_push($movs, $movimiento->id);

            $this->registrarPago($movimiento, $request);
        }
        return $movs;
    }

    public function registrarPesajeVenta($request, $movs, $input)
    {

        foreach ($request->lotesVentas as $ventaId) {
            $venta = Venta::find($ventaId);
            $obj = new CostoVentaController();

            $input['origen_id'] = $ventaId;
            $input['motivo'] = trim(sprintf("%s %s", 'GASTOS DE PESAJE DE MINERAL EN LOTE', $venta->lote));
            switch ($venta->letra) {
                case 'A':
                    $monto = $request->montoA;
                    break;
                case 'B':
                    $monto = $request->montoB;
                    break;
                case 'C':
                    $monto = $request->montoC;
                    break;
                case 'D':
                    $monto = $request->montoD;
                    break;
                case 'E':
                    $monto = $request->montoE;
                    break;
                case 'F':
                    $monto = $request->montoF;
                    break;
                case 'G':
                    $monto = $request->montoG;
                    break;
            }

            $input['monto'] = $monto;
            $input['venta_id'] = $ventaId;
            $input['descripcion'] = 'GASTOS DE PESAJE DE MINERAL';
            CostoVenta::create($input);

            $input['monto'] = $monto;
            $movimiento = Movimiento::create($input);

            array_push($movs, $movimiento->id);

            $this->registrarPago($movimiento, $request);
        }
        return $movs;
    }

    public function imprimirLaboratorio($pagosId)
    {
        $seleccionados = explode(",", $pagosId);
        $pagos = PagoMovimiento::whereOrigenType(Movimiento::class)->whereIn('origen_id', $seleccionados)->get();

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($pagos->sum('monto'), 2, 'BOLIVIANOS', 'CENTAVOS');
        $tipo = 'laboratorio';
        $vistaurl = "movimientos.imprimir_laboratorio";
        $view = \View::make($vistaurl, compact('pagos', 'literal', 'tipo'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboPagoLaboratorio-' . $pagos[0]->codigo . '.pdf');
    }

    public function imprimirPesaje($pagosId)
    {
        $seleccionados = explode(",", $pagosId);
        $pagos = PagoMovimiento::whereOrigenType(Movimiento::class)->whereIn('origen_id', $seleccionados)->get();

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($pagos->sum('monto'), 2, 'BOLIVIANOS', 'CENTAVOS');
        $tipo = 'pesaje';
        $vistaurl = "movimientos.imprimir_laboratorio";
        $view = \View::make($vistaurl, compact('pagos', 'literal', 'tipo'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboPagoPesaje-' . $pagos[0]->codigo . '.pdf');
    }


    private function registrarPago($movimiento, $request)
    {
        $objMov = new MovimientoController();
        $input['origen_type'] = Movimiento::class;
        $input['codigo'] = $objMov->getCodigo('Egreso');
        $input['monto'] = $movimiento->monto;
        $input['metodo'] = $request->metodo;
        $input['origen_id'] = $movimiento->id;
        $input['anio'] = date('y');
        if (date('m') >= 10)
            $input['anio'] = $input['anio'] + 1;

        if ($request->metodo == TipoPago::CuentaBancaria) {
            $input['banco'] = $request->banco;
            $input['glosa'] = $movimiento->motivo . ', en transferencia bancaria con recibo ' . $request->numero_recibo. $request->numero_recibo_pesaje;
        } else {
            $input['glosa'] = $movimiento->motivo;
            $input['banco'] = null;
        }
        $input['numero'] = $objMov->proximoOrden();
        PagoMovimiento::create($input);
    }



}
