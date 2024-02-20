<?php

namespace App\Http\Controllers;

use App\Models\Bono;
use App\Models\CuentaCobrar;
use App\Models\DescuentoBonificacion;
use App\Models\FormularioDescuento;
use App\Models\FormularioKardex;
use App\Models\FormularioLiquidacion;
use App\Patrones\ClaseDescuento;
use App\Patrones\ClaseDevolucion;
use App\Patrones\Fachada;
use App\Patrones\TipoDescuentoBonificacion;
use Illuminate\Http\Request;
use DB;

class ApiDescuentoBonificacionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])
                ->whereFormularioLiquidacionId($request->formulario_id)
                ->whereHas('descuentoBonificacion', function ($q) use ($request) {
                    $q->whereTipo($request->tipo)
                        ->whereClase(ClaseDescuento::EnLiquidacion)
                    ;
                })->orderBy('descuento_bonificacion_id')->get();

            return response()->json(['res' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function descuentosFaltantes(Request $request)
    {
        try {
            $formulario = FormularioLiquidacion::find($request->formulario_id);
            $descuentosDelFormulario = FormularioLiquidacion::find($request->formulario_id)->descuentoBonificaciones->pluck('id');
            $descuentos = \App\Models\DescuentoBonificacion::whereCooperativaId($formulario->cliente->cooperativa_id)->whereAlta(true)
                ->whereNotIn('id', $descuentosDelFormulario)
                ->whereClase(ClaseDescuento::EnLiquidacion)
                ->where('nombre', '<>', 'BONO CALIDAD')
                ->where('nombre', '<>', 'BONO PROVEEDOR')
                ->whereTipo($request->tipo)->get();

            return response()->json(['res' => true, 'descuentos' => $descuentos], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function store(Request $request)
    {
        $descuentoId = $request->descuento_id;
        $descuentoBonificacion = DescuentoBonificacion::find($descuentoId);

        $campos['formulario_liquidacion_id'] = $request->formulario_id;
        $campos['descuento_bonificacion_id'] = $descuentoId;
        $campos['valor'] = $descuentoBonificacion->valor;
        $campos['en_funcion'] = $descuentoBonificacion->en_funcion;
        $campos['unidad'] = $descuentoBonificacion->unidad;

        FormularioDescuento::create($campos);

        $formulario = FormularioLiquidacion::find($request->formulario_id);
        $this->actualizarSaldoYLiquido($formulario);
        $this->actualizarEnFormulario($formulario, $descuentoId);

        return response()->json(['res' => true, 'message' => 'Agregado correctamente!']);
    }

    public function destroy(Request $request)
    {
        $formulario_id = $request->formulario_id;
        $descuento_id = $request->descuento_id;

        $descuento=DescuentoBonificacion::find($descuento_id);
        $nombre = $descuento->nombre;
        \DB::select("delete from formulario_descuento where formulario_liquidacion_id = ? and descuento_bonificacion_id = ?", [$formulario_id, $descuento_id]);

        if($nombre=='BONO CALIDAD')
        {
            $form = FormularioLiquidacion::find($formulario_id);
            $valor = $form->valor_por_tonelada + 20;
            $form->update(['valor_por_tonelada' => $valor]);
        }
        $formulario = FormularioLiquidacion::find($request->formulario_id);
        $this->actualizarSaldoYLiquido($formulario);
        $this->actualizarEnFormulario($formulario, $request->descuento_id);

        return response()->json(['res' => true, 'message' => 'Eliminado correctamente!']);
    }

    private function actualizarEnFormulario($formulario, $descuentoId){
        $descuentoBonificacion = DescuentoBonificacion::find($descuentoId);

        if($descuentoBonificacion->tipo == TipoDescuentoBonificacion::Bonificacion)
            $formulario->update(['total_bonificacion' => $formulario->totales['total_bonificaciones']]);
        else{
            $formulario->update(['total_retencion_descuento' =>
                ($formulario->totales['total_retenciones'] + $formulario->totales['total_descuentos'])]);
        }

    }

    private function actualizarSaldoYLiquido($formulario){
        $formulario->update(['saldo_favor' => $formulario->totales['total_saldo_favor'],
            'liquido_pagable' => $formulario->totales['total_liquidacion']]);
    }

    public function getTotales(FormularioLiquidacion $formularioLiquidacion)
    {
        $retenciones = FormularioDescuento::whereFormularioLiquidacionId($formularioLiquidacion->id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo(TipoDescuentoBonificacion::Retencion);
            })->get();

        $descuentos = FormularioDescuento::whereFormularioLiquidacionId($formularioLiquidacion->id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo(TipoDescuentoBonificacion::Descuento);
            })->get();

        $bonificaciones = FormularioDescuento::whereFormularioLiquidacionId($formularioLiquidacion->id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo(TipoDescuentoBonificacion::Bonificacion)
                    ->whereClase(ClaseDescuento::EnLiquidacion);
            })->get();

        $bonificacionesAcumulativas = FormularioDescuento::whereFormularioLiquidacionId($formularioLiquidacion->id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo(TipoDescuentoBonificacion::Bonificacion)
                    ->whereClase(ClaseDescuento::Acumulativo);
            })->get();

        $totalMinerales = collect($formularioLiquidacion->mineralesRegalia)->sum('sub_total');

        $totalRetenciones = collect($retenciones)->sum('sub_total') + $totalMinerales;
        $totalDescuentos = collect($descuentos)->sum('sub_total');
        $totalBonificaciones = collect($bonificaciones)->sum('sub_total');
        $totalBonificacionesAcumulativas = collect($bonificacionesAcumulativas)->sum('sub_total');


        $totalCuentasCobrar = CuentaCobrar::whereOrigenType(FormularioLiquidacion::class)->whereOrigenId($formularioLiquidacion->id)->sum('monto');
        $totalAnticipos = $formularioLiquidacion->anticipos->sum('monto');
        $totalBonosExtras = $formularioLiquidacion->bonos->sum('monto');
        $totalDevolucionesInternas = Bono::whereFormularioLiquidacionId($formularioLiquidacion->id)->whereClase(ClaseDevolucion::Interno)->sum('monto');

        $totalFinal = ($formularioLiquidacion->valorNetoVenta + $totalBonificaciones - $totalBonosExtras) - ($totalRetenciones + $totalDescuentos + $totalAnticipos + $totalCuentasCobrar); //$formularioLiquidacion->anticipos->sum('monto');
        $totalAnticiposFEntrega = ($totalFinal >= $totalAnticipos || $totalFinal < 0) ? 0 : ($totalFinal - $totalAnticipos);

        $totales = [
            'total_minerales' => $totalMinerales,
            'total_retenciones' => $totalRetenciones,
            'total_descuentos' => $totalDescuentos,
            'total_bonificaciones' => $totalBonificaciones,
            'total_bonificaciones_acumulativas' => $totalBonificacionesAcumulativas,
            'total_anticipos' => $totalAnticipos,
            'total_bonos' => $totalBonosExtras,
            'total_devoluciones_internas' => $totalDevolucionesInternas,
            'total_liquidacion' => ($formularioLiquidacion->valorNetoVenta + $totalBonificaciones) - ($totalRetenciones + $totalDescuentos),
            'total_final' => $totalFinal,
            'total_anticipo_fentrega' => $totalAnticiposFEntrega,
            'total_saldo_favor' =>  $totalFinal - $formularioLiquidacion->aporte_fundacion,
            'total_cuentas_cobrar' => $totalCuentasCobrar
        ];

        return $totales;
    }


    public function getDescuentosBonificaciones(FormularioLiquidacion $formularioLiquidacion)
    {
        $transporte = $this->getDescuentoBonificacion($formularioLiquidacion, 'BONO TRANSPORTE');//'BONO TRANSPORTE');
        $ley = $this->getDescuentoBonificacion($formularioLiquidacion, 'BONO LEY');
        $viaticos = $this->getDescuentoBonificacion($formularioLiquidacion, 'BONO VIÁTICOS');
        $fencomin = $this->getDescuentoBonificacion($formularioLiquidacion, 'FENCOMIN');
        $administracion = $this->getDescuentoBonificacion($formularioLiquidacion, 'ADMINISTRACIÓN');
        $norpo = $this->getDescuentoBonificacion($formularioLiquidacion, 'FENCOMIN-NORPO');
        $pensiones = $this->getDescuentoBonificacion($formularioLiquidacion, 'SISTEMA INTEGRAL DE PENSIONES');
        $comibol = $this->getDescuentoBonificacion($formularioLiquidacion, 'COMIBOL');
        $caja = $this->getDescuentoBonificacion($formularioLiquidacion, 'CAJA NACIONAL DE SALUD');
        $descuentosBonificaciones = [
            'fencomin' => $fencomin,
            'administracion' => $administracion,
            'norpo' => $norpo,
            'pensiones' => $pensiones,
            'transporte' => $transporte,
            'ley' => $ley,
            'viaticos' => $viaticos,
            'caja' => $caja,
            'comibol' => $comibol
        ];
        return $descuentosBonificaciones;
    }

    private function getDescuentoBonificacion($formularioLiquidacion, $nombre)
    {
        $descuentosBonificaciones = FormularioDescuento::whereFormularioLiquidacionId($formularioLiquidacion->id)
            ->whereHas('descuentoBonificacion', function ($q) use ($nombre) {
                $q->whereNombre($nombre);
            })->first();
        $descuentoBonificacion = $descuentosBonificaciones ? $descuentosBonificaciones->descuentoBonificacion->valor : 0;
        return $descuentoBonificacion;
    }

    public function getDescuentosBonificacionesBob(FormularioLiquidacion $formularioLiquidacion)
    {
        $transporte = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'BONO TRANSPORTE');//'BONO TRANSPORTE');
        $productor = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'BONO PRODUCTOR');//'BONO TRANSPORTE');
        $ley = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'BONO LEY');
        $viaticos = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'BONO VIÁTICOS');
        $fencomin = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'FENCOMIN');
        $administracion = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'ADMINISTRACIÓN');
        $norpo = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'FENCOMIN-NORPO');
        $pensiones = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'SISTEMA INTEGRAL DE PENSIONES');
        $comibol = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'COMIBOL');
        $caja = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'CAJA NACIONAL DE SALUD');
        $descuentosBonificaciones = [
            'fencomin' => $fencomin,
            'administracion' => $administracion,
            'norpo' => $norpo,
            'pensiones' => $pensiones,
            'transporte' => $transporte,
            'ley' => $ley,
            'viaticos' => $viaticos,
            'caja' => $caja,
            'comibol' => $comibol,
            'productor' => $productor
        ];
        return $descuentosBonificaciones;
    }

    public function getDescuentosBonificacionesBobKardex(FormularioKardex $formularioLiquidacion)
    {
        $transporte = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'BONO TRANSPORTE');//'BONO TRANSPORTE');
        $ley = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'BONO LEY');
        $viaticos = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'BONO VIÁTICOS');
        $fencomin = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'FENCOMIN');
        $administracion = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'ADMINISTRACIÓN');
        $norpo = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'FENCOMIN-NORPO');
        $pensiones = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'SISTEMA INTEGRAL DE PENSIONES');
        $comibol = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'COMIBOL');
        $caja = $this->getDescuentoBonificacionBOB($formularioLiquidacion, 'CAJA NACIONAL DE SALUD');
        $descuentosBonificaciones = [
            'fencomin' => $fencomin,
            'administracion' => $administracion,
            'norpo' => $norpo,
            'pensiones' => $pensiones,
            'transporte' => $transporte,
            'ley' => $ley,
            'viaticos' => $viaticos,
            'caja' => $caja,
            'comibol' => $comibol
        ];
        return $descuentosBonificaciones;
    }

    private function getDescuentoBonificacionBOB($formularioLiquidacion, $nombre)
    {
        $descuentosBonificaciones = FormularioDescuento::whereFormularioLiquidacionId($formularioLiquidacion->id)
            ->whereHas('descuentoBonificacion', function ($q) use ($nombre) {
                $q->whereNombre($nombre);
            })->first();
        $descuentoBonificacion = $descuentosBonificaciones ? $descuentosBonificaciones->sub_total : 0;
        return $descuentoBonificacion;
    }

    private function getDescuentoBonificacionBOBConNombre($formularioLiquidacion, $nombre, $tipo)
    {
        $descuentosBonificaciones = FormularioDescuento::whereFormularioLiquidacionId($formularioLiquidacion->id)
            ->whereHas('descuentoBonificacion', function ($q) use ($nombre, $tipo) {
                $q->whereNombre($nombre)->whereTipo($tipo);
            })->first();
        $descuentoBonificacion = $descuentosBonificaciones ? $descuentosBonificaciones->sub_total : 0;
        return $descuentoBonificacion;
    }

    public function getRetencionesCooperativa($idCooperativa, $formularioId)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($formularioId);
        $arrayRetenciones = [];
        $descuentoBonificacionCooperativa = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Retencion)->get();
        foreach ($descuentoBonificacionCooperativa as $retencion){
            $arrayRetenciones += array($retencion->nombre =>
                $this->getDescuentoBonificacionBOBConNombre($formularioLiquidacion, $retencion->nombre, TipoDescuentoBonificacion::Retencion));
        }
        return $arrayRetenciones;
    }

    public function getDescuentosCooperativa($idCooperativa, $formularioId)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($formularioId);
        $arrayDescuentos = [];
        $descuentoBonificacionCooperativa = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Descuento)->get();
        foreach ($descuentoBonificacionCooperativa as $descuento){
            $arrayDescuentos += array($descuento->nombre =>
                $this->getDescuentoBonificacionBOBConNombre($formularioLiquidacion, $descuento->nombre, TipoDescuentoBonificacion::Descuento));
        }
        return $arrayDescuentos;
    }

    public function getBonificacionesCooperativa($idCooperativa, $formularioId)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($formularioId);
        $arrayBonificaciones = [];
        $descuentoBonificacionCooperativa = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Bonificacion)->get();
        foreach ($descuentoBonificacionCooperativa as $bonificacion){
            $arrayBonificaciones += array($bonificacion->nombre =>
                $this->getDescuentoBonificacionBOBConNombre($formularioLiquidacion, $bonificacion->nombre, TipoDescuentoBonificacion::Bonificacion));
        }

        return $arrayBonificaciones;
    }
}
