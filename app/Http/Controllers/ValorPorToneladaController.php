<?php

namespace App\Http\Controllers;

use App\Models\BasePlomoPlata;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\CostosPlata;
use App\Models\CotizacionDiaria;
use App\Models\CotizacionOficial;
use App\Models\DescuentoBonificacion;
use App\Models\FormularioDescuento;
use App\Models\FormularioLiquidacion;
use App\Models\Laboratorio;
use App\Models\LiquidacionMineral;
use App\Models\MargenTermino;
use App\Models\Material;
use App\Models\ProductoMineral;
use App\Models\TablaAcopiadora;
use App\Models\TerminosPlata;
use App\Models\TerminosPlomoPlata;
use App\Models\TipoCambio;
use App\Patrones\TipoMaterial;
use App\Patrones\UnidadCotizacion;
use Illuminate\Http\Request;

class ValorPorToneladaController extends Controller
{
    public function updateValorPorTonelada($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($id);

        if (str_contains($formularioLiquidacion->producto, 'Estaño')) {
            $valorPorTonelada = $this->getValorPorTonEstano($formularioLiquidacion);
            if ($valorPorTonelada === -1) { // si no se encuentra en la matriz
                //$formularioLiquidacion->valor_por_tonelada = null;
                $formularioLiquidacion->save();
                return response()->json(['res' => false, 'formulario' => $formularioLiquidacion, 'message' => "No se encontró un valor para la cotizacion y la ley"]);
            }
            $formularioLiquidacion->valor_por_tonelada = $valorPorTonelada;
            $formularioLiquidacion->save();
        } elseif (str_contains($formularioLiquidacion->producto, 'Zinc')) {
            $valorPorTonelada = $this->getValorPorTonZinc($formularioLiquidacion);

            $formularioLiquidacion->valor_por_tonelada = $valorPorTonelada;
            $formularioLiquidacion->save();
        } elseif (str_contains($formularioLiquidacion->producto, 'Plomo')) {
            $valorPorTonelada = $this->getValorPorTonPlomo($formularioLiquidacion);

            $formularioLiquidacion->valor_por_tonelada = $valorPorTonelada;
            $formularioLiquidacion->save();
        } elseif ($formularioLiquidacion->letra == 'E') {
            $valorPorTonelada = $this->getValorPorTonPlata($id);

            $formularioLiquidacion->valor_por_tonelada = $valorPorTonelada;
            $formularioLiquidacion->save();
        } elseif ($formularioLiquidacion->letra == 'F') {
            $valorPorTonelada = $this->getValorPorTonAntimonio($formularioLiquidacion);
            $formularioLiquidacion->valor_por_tonelada = $valorPorTonelada;
            $formularioLiquidacion->save();
        } elseif ($formularioLiquidacion->letra == 'G') {
            $valorPorTonelada = $this->getValorPorTonCobre($formularioLiquidacion);

            $formularioLiquidacion->valor_por_tonelada = $valorPorTonelada;
            $formularioLiquidacion->save();
        } else {
            $formularioLiquidacion->valor_por_tonelada = null;
            $formularioLiquidacion->save();
        }

        if($formularioLiquidacion->valor_restado){
            $formularioLiquidacion->valor_por_tonelada = ($valorPorTonelada - 20);
            $formularioLiquidacion->save();
        }

        $this->actualizarValores($formularioLiquidacion);
        return response()->json(['res' => true, 'formulario' => $formularioLiquidacion]);
    }

    private function actualizarValores($formulario)
    {
        FormularioLiquidacion::where('id', $formulario->id)
            ->update(['neto_venta' => $formulario->valor_neto_venta
                , 'saldo_favor' => $formulario->totales['total_saldo_favor'], 'liquido_pagable' => $formulario->totales['total_liquidacion']]);
    }

    public function getValorPorTonPlata($id)
    {
        $form = FormularioLiquidacion::find($id);
        $ley = $this->leyZincPlomo($form->id, 'Ag');

        $cot = $form->cotizacion_promedio_ag;

        $cienPorCien = ($ley * 100 / 31.1035) * $cot;
        if ($form->tipo_material == TipoMaterial::Concentrado or $form->tipo_material == TipoMaterial::Guiamina) {
            if ($ley < 15.00)
                return 0;
            if ($form->cliente->cooperativa_id == 44) {
                $pesoFino = ($form->ley_ag / 10) * ($form->peso_neto_seco / 1000);
                $valor = (($pesoFino * 1000 / 31.1035) * $cot);
            } else {
                $valor = $cienPorCien * $this->getPagablePlata($ley, $form->fecha_liquidacion) / 100;
            }

            if ($form->tipo_material == TipoMaterial::Guiamina)
                $valor = $valor - 10;
        } else {
            if ($ley < 4.00 or $ley >= 20.00)
                return 0;

            $costos = CostosPlata::sum('monto');
            $valor = ($cienPorCien * 0.70) - ($cienPorCien * 0.024) - $costos;
        }
        return $valor;
    }

    public function getPagablePlata($ley, $fechaLiquidacion)
    {
        if ($ley >= 600.00)
            return 83.75;

        if ($ley < 15.00)
            return 0.00;

        if (intval($ley) == 19 and $fechaLiquidacion < '2023-11-23')
            return 60;
        $menor = TerminosPlata::where('ley', '<=', $ley)->orderByDesc('ley')->first();
        $ley1 = $menor->ley;
        $pagable1 = $menor->pagable;
        $mayor = TerminosPlata::where('ley', '>', $ley)->orderBy('ley')->first();
        $ley2 = $mayor->ley;
        $pagable2 = $mayor->pagable;
        $pagable = $pagable1 + ((($pagable2 - $pagable1) / ($ley2 - $ley1)) * ($ley - $ley1));

        return $pagable;
    }

    public function getValorPorTonEstano($f)
    {
        $tablaAcop = TablaAcopiadora::whereEsSeleccionado(true)->first();

        if (is_null($tablaAcop))
            return -1;

        $laboratorios = collect($f->laboratorioPromedio)->where('simbolo', 'Sn');
        $ley = $laboratorios->first()->promedio;

        $liquidacionMineral = LiquidacionMineral::whereMineralId(4)->whereFormularioLiquidacionId($f->id)->first();

        if (($ley < $liquidacionMineral->ley_minima) and $f->con_ley_minima)
            $ley = 0;

        $cotizacion = $this->getCotizacionDiaria($f)[0]->monto / 2204.6223;

        //restando el margen a la cotizacion
        $cotizacion = $cotizacion + ($tablaAcop->margen);
        $cotizacion = round($cotizacion, 2);
        //return $cotizacion;

        $tad = $tablaAcop->tablaAcopiadoraDetalles()->where('cotizacion', $cotizacion)->first();
        if (is_null($tad))
            return -1;
        if ($ley >= 5.00 and $ley <= 10.00) {
            return $this->getEstanioMenor10($ley, $tad);
        }

        $valorPorTonelada = $tad["l_{$ley}"];
        //si no existe realizamos una interpolacion simple
        if (is_null($valorPorTonelada)) {
            $leyInicial = $this->getLeyInferior($ley);
            $leyFinal = $this->getLeySuperior($ley);
            $vptInicial = $tad['l_' . $leyInicial];
            $vptFinal = $tad['l_' . $leyFinal];

            if (is_null($vptInicial) || is_null($vptFinal))
                return -1;

            $valorPorTonelada = $vptInicial + (($vptFinal - $vptInicial) / ($leyFinal - $leyInicial)) * ($ley - $leyInicial);
        }

//        if($tablaAcop->id==1)
//            return round($valorPorTonelada, 2);
//        else
        $valor = $valorPorTonelada - ($valorPorTonelada * 0.12) - 230;
        if ($valor < 0.00)
            $valor = 0.00;
        return round($valor, 2);
    }


    private function getEstanioMenor10($ley, $tad)
    {
        $ley10 = 10.00;
        $ley5 = 5.00;
        $valorPorTonelada10 = $tad["l_{$ley10}"];
        $valor10 = $valorPorTonelada10 - ($valorPorTonelada10 * 0.12) - 230;
        $valor5 = $tad["l_{$ley5}"];

        $valorPorTonelada = $valor5 + (($valor10 - $valor5) / (5.00)) * ($ley - 5.00);

        $valorPorTonelada = $valorPorTonelada * 1.4;
        return round($valorPorTonelada, 2);
    }

    private function getLeyInferior($ley)
    {
        $ley = floor($ley);
        if ((int)$ley % 5 === 0)
            return $ley;
        else
            return $this->getLeyInferior($ley - 1);
    }

    private function getLeySuperior($ley)
    {
        $ley = ceil($ley);
        if ((int)$ley % 5 === 0)
            return $ley;
        else
            return $this->getLeySuperior($ley + 1);
    }

    private function getCotizacionDiaria($f)
    {
        return CotizacionDiaria::with(['mineral:id,simbolo,nombre'])
            ->whereFecha($f->fecha_cotizacion)
            ->whereIn('mineral_id', $f->liquidacioMinerales->pluck('mineral_id'))
            ->get();
    }

    public function getValorPorTonPlomo($formulario)
    {
        $leyPb = $this->leyZincPlomo($formulario->id, 'Pb');
        $leyAg = $this->leyZincPlomo($formulario->id, 'Ag');


        if (($leyPb + $leyAg) < 30)
            return null;


        $contrato = Contrato::whereProductoId(2)->first();

        $transporteInterno = $this->transporteZincPlomo($formulario->id);

        $plomo = $this->cotizacionZincPlomo($formulario->id, 2);
        $plata = $this->cotizacionZincPlomo($formulario->id, 1);

        if ($formulario->id == 9172) {
            $plata = 24.878;
            $plomo = 2118;
        }

        if ($formulario->id == 9539 or $formulario->id == 9559) {
            $plata = 23.938;
            $plomo = 2038.5;
        }

        if ($formulario->id == 10358) {
            $plata = 22.978;
            $plomo = 2027.6;
        }

        $deduccionUnitariaPb = $contrato->deduccion_elemento ?? 0;
        $porcentajePagablePb = $contrato->porcentaje_pagable_elemento ?? 0;
        $deduccionUnitariaAg = $contrato->deduccion_plata;
        $porcentajePagableAg = $contrato->porcentaje_pagable_plata;
        $maquila = $contrato->maquila;
        $base = $contrato->base;
        $escalador = $contrato->escalador;
        $gastoRefinacion = $contrato->deduccion_refinacion_onza;

        $rollBack = $contrato->roll_back;
        $bonoCliente = $contrato->bono_cliente;
        $bonoProductor = $contrato->bono_productor;
        $bonoEquipamiento = $contrato->bono_equipamiento;
        $bonoEpp = $contrato->bono_epp;
        //     $transporteInterno = $contrato->transporte_interno;
        $transporteExportacion = $contrato->transporte_exportacion;
        $laboratorioInterno = $contrato->laboratorio_interno;
        $bonoRefrigerio = $contrato->bono_refrigerio;
        $publicidad = $contrato->publicidad;

        $resultadoPb1 = ($porcentajePagablePb / 100) * $leyPb;
        $resultadoPb2 = $leyPb + $deduccionUnitariaPb;
        $minPagablePb = $resultadoPb1;
        if ($resultadoPb2 < $resultadoPb1)
            $minPagablePb = $resultadoPb2;

        if (($leyPb + $leyAg) >= 60)
            $margen = 9;

        else {
            $inferior = MargenTermino::where('ley', '<=', $leyPb + $leyAg)->orderByDesc('ley')->first();
            $margenInferior = $inferior->margen_pb;
            $leyInferior = $inferior->ley;

            $superior = MargenTermino::where('ley', '>=', $leyPb + $leyAg)->orderBy('ley')->first();
            $leySuperior = $superior->ley;
            $margenSuperior = $superior->margen_pb;


            if ($margenInferior == $margenSuperior)
                $margen = $margenInferior;

            else
                $margen = $margenInferior + (($margenSuperior - $margenInferior) / ($leySuperior - $leyInferior)) * (($leyPb + $leyAg) - $leyInferior);

        }
        $margen = $margen / 100;


        $alicuotaInternaPb = 0;
        $alicuotaInternaAg = 0;
        $alicuotaExportacionPb = 0;
        $alicuotaExportacionAg = 0;
        $valorBrutoPb = 0;
        $valorBrutoAg = 0;

        foreach ($formulario->minerales_regalia as $mineral) {
            $mineral = (object)$mineral;
            if ($mineral->simbolo == 'Pb') {
                $alicuotaInternaPb = $mineral->alicuota_interna;
                $alicuotaExportacionPb = $mineral->alicuota_externa;
                $valorBrutoPb = $mineral->valor_bruto_venta;
            } else {
                $alicuotaInternaAg = $mineral->alicuota_interna;
                $alicuotaExportacionAg = $mineral->alicuota_externa;
                $valorBrutoAg = $mineral->valor_bruto_venta;
            }
        }

        $regaliaExternaPb = ((($alicuotaExportacionPb - $alicuotaInternaPb) / 100) * $valorBrutoPb / $formulario->tipoCambio->dolar_venta) / ($formulario->peso_neto_seco / 1000);
        $regaliaExternaAg = ((($alicuotaExportacionAg - $alicuotaInternaAg) / 100) * $valorBrutoAg / $formulario->tipoCambio->dolar_venta) / ($formulario->peso_neto_seco / 1000);
        $pesoBruto = $formulario->peso_bruto;
        $pesoNetoSeco = $formulario->peso_neto_seco;

        $operacionBaseEscalador = 0;
        if ($plomo > $base)
            $operacionBaseEscalador = (($plomo - $base) * $escalador);

        $operacion = ((($plomo * $minPagablePb / 100)) + (((((($leyAg * 100) / 31.1035))) + $deduccionUnitariaAg) *
                    ($porcentajePagableAg / 100 * $plata))
                - $maquila - $operacionBaseEscalador
                - ((((($leyAg * 100) / 31.1035) + $deduccionUnitariaAg) * $porcentajePagableAg / 100) * $gastoRefinacion)

                - (($rollBack) * (($pesoBruto / 1000) / ($pesoNetoSeco / 1000)))
            ) * (1 - $margen) - ($bonoProductor + $bonoCliente + $bonoEpp + $bonoEquipamiento)
            - (($transporteExportacion + $transporteInterno) * (($pesoBruto / 1000) / ($pesoNetoSeco / 1000))) -
            (($laboratorioInterno + $bonoRefrigerio + $publicidad) / ($pesoNetoSeco / 1000))
            - $regaliaExternaAg - $regaliaExternaPb;


        if ($formulario->tipo_material == TipoMaterial::Guiamina)
            $operacion = $operacion - 10;

        return ($operacion);
    }

    private function getTerminosPbAg($sumaLey)
    {
        return TerminosPlomoPlata::where('ley_minima', '<=', $sumaLey)->where('ley_maxima', '>=', $sumaLey)->first();
    }

    private function getBasePbAg($cotizacion)
    {
        $item = BasePlomoPlata::where('lme_pb_minimo', '<=', $cotizacion)->where('lme_pb_maximo', '>=', $cotizacion)->first();
        return $item->base;
    }

    private function getValorPorTonAntimonio($formulario)
    {
        $formularioId = $formulario->id;
        $antimonio = $this->cotizacionZincPlomo($formularioId, 6);
        $leySb = $this->leyZincPlomo($formularioId, 'Sb');
        $porcentaje = 0;

        if ($leySb >= 10.00 and $leySb <= 19.99)
            $porcentaje = 0.10;
        elseif ($leySb >= 20.00 and $leySb <= 29.99)
            $porcentaje = 0.24;
        elseif ($leySb >= 30.00 and $leySb <= 49.99)
            $porcentaje = 0.30;
        elseif ($leySb >= 50.00 and $leySb <= 59.99)
            $porcentaje = 0.44;
        elseif ($leySb >= 60.00 and $leySb <= 70.00)
            $porcentaje = 0.54;
        elseif ($leySb >= 5 and $leySb <= 5.99)
            $porcentaje = 0.05;
        elseif ($leySb >= 6 and $leySb <= 6.99)
            $porcentaje = 0.06;
        elseif ($leySb >= 7 and $leySb <= 7.99)
            $porcentaje = 0.07;
        elseif ($leySb >= 8 and $leySb <= 8.99)
            $porcentaje = 0.08;
        elseif ($leySb >= 9 and $leySb <= 9.99)
            $porcentaje = 0.09;

        $leySb = $leySb / 100;

        $valor = ($leySb * $porcentaje * $antimonio);
        if ($formulario->tipo_material == TipoMaterial::Guiamina)
            $valor = $valor - 10;
        return $valor;
    }

    private function getValorPorTonCobre($formulario)
    {
        $formularioId = $formulario->id;
        $contrato = Contrato::whereProductoId(7)->first();

        $leyCu = $this->leyZincPlomo($formularioId, 'Cu');
        $cobre = $this->cotizacionZincPlomo($formularioId, 5);
        $transporteInterno = $this->transporteZincPlomo($formulario->id);

        $deduccionUnitariaCu = $contrato->deduccion_elemento ?? 0;
        $porcentajePagableCu = $contrato->porcentaje_pagable_elemento ?? 0;
        $maquila = $contrato->maquila;
        $base = $contrato->base;
        $escalador = $contrato->escalador;
        $rollBack = $contrato->roll_back;
        $bonoCliente = $contrato->bono_cliente;
        $bonoProductor = $contrato->bono_productor;
        $bonoEquipamiento = $contrato->bono_equipamiento;
        $bonoEpp = $contrato->bono_epp;
        $transporteExportacion = $contrato->transporte_exportacion;
        $laboratorioInterno = $contrato->laboratorio_interno;
        $bonoRefrigerio = $contrato->bono_refrigerio;
        $publicidad = $contrato->publicidad;


        $resultadoCu1 = ($porcentajePagableCu / 100) * $leyCu;
        $resultadoCu2 = $leyCu + $deduccionUnitariaCu;
        $minPagableCu = $resultadoCu1;
        if ($resultadoCu2 < $resultadoCu1)
            $minPagableCu = $resultadoCu2;

        $pesoBruto = $formulario->peso_bruto;
        $pesoNetoSeco = $formulario->peso_neto_seco;

        if (($leyCu) >= 60)
            $margen = 9;

        if (($leyCu) >= 8 and $leyCu < 10)
            $margen = 10.5;

        else {
            $inferior = MargenTermino::where('ley', '<=', $leyCu)->orderByDesc('ley')->first();
            $margenInferior = $inferior->margen_cu;
            $leyInferior = $inferior->ley;

            $superior = MargenTermino::where('ley', '>=', $leyCu)->orderBy('ley')->first();
            $leySuperior = $superior->ley;
            $margenSuperior = $superior->margen_cu;


            if ($margenInferior == $margenSuperior)
                $margen = $margenInferior;

            else
                $margen = $margenInferior + (($margenSuperior - $margenInferior) / ($leySuperior - $leyInferior)) * (($leyCu) - $leyInferior);

        }

        $margen = $margen / 100;


//

        $alicuotaInternaCu = 0;
        $alicuotaExportacionCu = 0;
        $valorBrutoCu = 0;

        foreach ($formulario->minerales_regalia as $mineral) {
            $mineral = (object)$mineral;
            if ($mineral->simbolo == 'Cu') {
                $alicuotaInternaCu = $mineral->alicuota_interna;
                $alicuotaExportacionCu = $mineral->alicuota_externa;
                $valorBrutoCu = $mineral->valor_bruto_venta;
            }

        }

        $regaliaExternaCu = ((($alicuotaExportacionCu - $alicuotaInternaCu) / 100) * $valorBrutoCu / $formulario->tipoCambio->dolar_venta) / ($formulario->peso_neto_seco / 1000);
        //

        $operacionBaseEscalador = 0;
        if ($cobre > $base)
            $operacionBaseEscalador = (($cobre - $base) * $escalador);

        $operacion = ((($cobre * $minPagableCu / 100))
                - $maquila - $operacionBaseEscalador

                - (($rollBack) * (($pesoBruto / 1000) / ($pesoNetoSeco / 1000)))
            ) * (1 - $margen) - ($bonoProductor + $bonoCliente + $bonoEpp + $bonoEquipamiento)
            - (($transporteExportacion + $transporteInterno) * (($pesoBruto / 1000) / ($pesoNetoSeco / 1000))) -
            (($laboratorioInterno + $bonoRefrigerio + $publicidad) / ($pesoNetoSeco / 1000))
            - $regaliaExternaCu;

        if ($formulario->tipo_material == TipoMaterial::Guiamina)
            $operacion = $operacion - 10;
        return $operacion;
    }

    public function getValorPorTonZinc($formulario)
    {
        $formularioId = $formulario->id;
        $leyZn = $this->leyZincPlomo($formularioId, 'Zn');
        $leyAg = $this->leyZincPlomo($formularioId, 'Ag');

        if (($leyZn + $leyAg) < 30)
            return null;


        $contrato = Contrato::whereProductoId(1)->first();

        $transporteInterno = $this->transporteZincPlomo($formulario->id);
        $zinc = $this->cotizacionZincPlomo($formularioId, 3);//2204.6223;
        $plata = $this->cotizacionZincPlomo($formularioId, 1);
        $porcentajePagableZn = $contrato->porcentaje_pagable_elemento;
        $deduccionUnitariaZn = $contrato->deduccion_elemento;
        $porcentajePagableAg = $contrato->porcentaje_pagable_plata;
        $deduccionUnitariaAg = $contrato->deduccion_plata;
        $maquila = $contrato->maquila;
        $base = $contrato->base;
        $escalador = $contrato->escalador;
        $rollBack = $contrato->roll_back;
        $bonoCliente = $contrato->bono_cliente;
        $bonoProductor = $contrato->bono_productor;
        $bonoEquipamiento = $contrato->bono_equipamiento;
        $bonoEpp = $contrato->bono_epp;
        $transporteExportacion = $contrato->transporte_exportacion;
        $laboratorioInterno = $contrato->laboratorio_interno;
        $bonoRefrigerio = $contrato->bono_refrigerio;
        $publicidad = $contrato->publicidad;

        $pesoBruto = $formulario->peso_bruto;
        $pesoSeco = $formulario->peso_neto_seco;

        $inferior = MargenTermino::where('ley', '<=', $leyZn + $leyAg)->orderByDesc('ley')->first();
        $margenInferior = $inferior->margen_zn;
        $leyInferior = $inferior->ley;

        $superior = MargenTermino::where('ley', '>=', $leyZn + $leyAg)->orderBy('ley')->first();
        $leySuperior = $superior->ley;
        $margenSuperior = $superior->margen_zn;


        if ($margenInferior == $margenSuperior)
            $margen = $margenInferior;

        else
            $margen = $margenInferior + (($margenSuperior - $margenInferior) / ($leySuperior - $leyInferior)) * (($leyZn + $leyAg) - $leyInferior);

        $margen = $margen / 100;

        $operacionBaseEscalador = 0;
        if ($zinc > $base)
            $operacionBaseEscalador = (($zinc - $base) * $escalador);

        $resultadoZn1 = ($porcentajePagableZn / 100) * $leyZn;
        $resultadoZn2 = $leyZn + $deduccionUnitariaZn;
        $minPagableZn = $resultadoZn1;
        if ($resultadoZn2 < $resultadoZn1)
            $minPagableZn = $resultadoZn2;


        $alicuotaInternaZn = 0;
        $alicuotaInternaAg = 0;
        $alicuotaExportacionZn = 0;
        $alicuotaExportacionAg = 0;
        $valorBrutoZn = 0;
        $valorBrutoAg = 0;

        foreach ($formulario->minerales_regalia as $mineral) {
            $mineral = (object)$mineral;
            if ($mineral->simbolo == 'Zn') {
                $alicuotaInternaZn = $mineral->alicuota_interna;
                $alicuotaExportacionZn = $mineral->alicuota_externa;
                $valorBrutoZn = $mineral->valor_bruto_venta;
            } else {
                $alicuotaInternaAg = $mineral->alicuota_interna;
                $alicuotaExportacionAg = $mineral->alicuota_externa;
                $valorBrutoAg = $mineral->valor_bruto_venta;
            }
        }

        $regaliaExternaZn = ((($alicuotaExportacionZn - $alicuotaInternaZn) / 100) * $valorBrutoZn / $formulario->tipoCambio->dolar_venta) / ($formulario->peso_neto_seco / 1000);
        $regaliaExternaAg = ((($alicuotaExportacionAg - $alicuotaInternaAg) / 100) * $valorBrutoAg / $formulario->tipoCambio->dolar_venta) / ($formulario->peso_neto_seco / 1000);


        $operacion =
            (
                ((
                        round($minPagableZn * $zinc / 100, 2)
                        + round($plata * ($porcentajePagableAg / 100) * round(((($leyAg * 100) / 31.1035) + $deduccionUnitariaAg), 3), 2)
                    )
                    - $maquila - $operacionBaseEscalador - ($rollBack * (($pesoBruto / 1000) / ($pesoSeco / 1000)))) * (1 - $margen)
                - ($bonoCliente + $bonoProductor + $bonoEquipamiento + $bonoEpp)
                - (($transporteExportacion + $transporteInterno) * (($pesoBruto / 1000) / ($pesoSeco / 1000)))
                - (($laboratorioInterno + $bonoRefrigerio + $publicidad) / ($pesoSeco / 1000))
                - $regaliaExternaAg - $regaliaExternaZn
            );


        if ($formulario->tipo_material == TipoMaterial::Guiamina)
            $operacion = $operacion - 10;

        return $operacion;

    }

    private function leyZincPlomo($formularioId, $simbolo)
    {
        $formulario = FormularioLiquidacion::find($formularioId);
//        $letra = $formulario->letra;

        $laboratorios = collect($formulario->laboratorioPromedio)->where('simbolo', $simbolo);
        $ley = $laboratorios->first()->promedio;

        $mineral = Material::whereSimbolo($simbolo)->first();
        $liquidacionMineral = LiquidacionMineral::whereMineralId($mineral->id)->whereFormularioLiquidacionId($formularioId)->first();

//        if ($ley < $liquidacionMineral->ley_minima and $formulario->con_ley_minima)
//            $ley = 0;


        return $ley;
    }

    private function transporteZincPlomo($formularioId)
    {
        $formulario = FormularioLiquidacion::find($formularioId);
        $cliente = Cliente::find($formulario->cliente_id);

        $bonificacion = DescuentoBonificacion::where('nombre', 'ilike', '%TRANSPORTE%')->whereAlta(true)
            ->whereCooperativaId($cliente->cooperativa_id)->first();

        $transporte = 30;
        if ($bonificacion) {
            $formularioDescuento = FormularioDescuento::whereFormularioLiquidacionId($formularioId)
                ->whereDescuentoBonificacionId($bonificacion->id)->count();
            if ($formularioDescuento == 1)
                $transporte = $bonificacion->valor;
        }
        return $transporte;
    }

    private function cotizacionZincPlomo($formularioId, $mineralId)
    {
        $form = FormularioLiquidacion::find($formularioId);
        $cotizacion = CotizacionDiaria::whereMineralId($mineralId)->whereFecha($form->fecha_cotizacion)->first();
        $valor = $cotizacion->monto;
        if ($cotizacion->unidad == UnidadCotizacion::LF and ($mineralId != 1))
            $valor = $cotizacion->monto * 2204.6223;
        return $valor;
    }

    public function restarValor($id)
    {
        \DB::beginTransaction();
        try {
            $form = FormularioLiquidacion::find($id);
            $valor = $form->valor_por_tonelada - 20;
            $bonificacion = DescuentoBonificacion::whereNombre('BONO CALIDAD')->whereCooperativaId($form->cliente->cooperativa_id)->whereAlta(true)->first();

            $form->update(['valor_por_tonelada' => $valor]);

            $input["formulario_liquidacion_id"] = $id;
            $input["descuento_bonificacion_id"] = $bonificacion->id;
            $input["valor"] = $bonificacion->valor;
            $input["en_funcion"] = $bonificacion->en_funcion;
            $input["unidad"] = $bonificacion->unidad;
            FormularioDescuento::create($input);
            $form = FormularioLiquidacion::find($id);
            \DB::commit();
            $this->actualizarValores($form);
            return response()->json(['res' => true, 'formulario' => $form]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }
}
