<?php

namespace App\Http\Controllers;

use App\Models\BasePlomoPlata;
use App\Models\Contrato;
use App\Models\CostosPlata;
use App\Models\CotizacionDiaria;
use App\Models\CotizacionOficial;
use App\Models\MargenTermino;
use App\Models\Material;
use App\Models\Producto;
use App\Models\TablaAcopiadora;
use App\Models\TerminosPlomoPlata;
use App\Models\TipoCambio;
use App\Patrones\Empaque;
use App\Patrones\TipoMaterial;
use App\Patrones\UnidadCotizacion;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
use function PHPUnit\Framework\isEmpty;

class CotizadorAndroidController
{

    public function getValorPorTonelada(Request $request)
    {
        $leyAg = floatval(str_replace(',','.', $request->leyAg));
        $leyZn = floatval(str_replace(',','.', $request->leyZn));
        $leyPb = floatval(str_replace(',','.', $request->leyPb));
        $leySn = floatval(str_replace(',','.', $request->leySn));
        $leyCu = floatval(str_replace(',','.', $request->leyCu));
        $leySb = floatval(str_replace(',','.', $request->leySb));
        $transporte = 30;
        $productoId = $request->productoId;
        $tipoMaterial = $request->tipoMaterial;
        $fecha = $request->fecha;
        if(is_null($fecha))
            $fecha = date('Y-m-d');
//        $fechaCot = date('Y-m-d');
//        if(!is_null($request->fecha))
//            $fechaCot = $request->fecha;

        $valorPorTonelada = '';
        $diaria = '';

        try {
            if ($productoId == '4') {
                $valorPorTonelada = round($this->getValorPorTonEstano($leySn, $fecha), 2);
                $diaria = 'SN:' . $this->getDiaria(4, $fecha)["valor"];
                if ($valorPorTonelada === -1) { // si no se encuentra en la matriz
                    return response()->json(['res' => false, 'message' => $valorPorTonelada]);
                }
            } elseif ($productoId == '1') {
                $valorPorTonelada = round($this->getValorPorTonZinc($leyAg, $leyZn, $transporte, $tipoMaterial, $fecha), 2);
                $diaria = 'ZN: ' . $this->getDiaria(3, $fecha)["valor"] . ', AG: ' . $this->getDiaria(1, $fecha)["valor"];

            } elseif ($productoId == '2') {
                $valorPorTonelada = round($this->getValorPorTonPlomo($leyAg, $leyPb, $transporte, $tipoMaterial, $fecha), 2);
                $diaria = 'PB: ' . $this->getDiaria(2, $fecha)["valor"] . ', AG: ' . $this->getDiaria(1, $fecha)["valor"];
            }
            elseif ($productoId == '5') {
                $valorPorTonelada = round($this->getValorPorTonAntimonio($leySb, $tipoMaterial, $fecha), 2);
                $diaria = 'SB: ' . $this->getDiaria(6, $fecha)["valor"];
            }

            elseif ($productoId == '6') {
                $valorPorTonelada = round($this->getValorPorTonPlata($leyAg, $tipoMaterial, $fecha), 2);
                $diaria = 'AG: ' . $this->getDiaria(1, $fecha)["valor"];
            }
            elseif ($productoId == '7') {
                $valorPorTonelada = round($this->getValorPorTonCobre($leyCu, $tipoMaterial, $fecha), 2);
                $diaria = 'CU: ' . $this->getDiaria(5, $fecha)["valor"];
            }
            $valorPorKilo = round((($valorPorTonelada/1000)*6.86), 2);
            return response()->json(['diaria' => $diaria, 'valor' => $valorPorTonelada . ' USD', 'kilo' => $valorPorKilo . ' BOB', 'existe' => $this->getDiaria(1, $fecha)["existe"]]);
        } catch (\Exception $e) {
            return response()->json(['diaria' => 'Error: revise los valores introducidos', 'valor' => 'Error: revise los valores introducidos']);
        }
    }

    public function getValorPorTonPlata($ley, $tipo, $fecha)
    {
        $cot = $this->getCotizacionDiaria(1, $fecha);
        $cienPorCien = ($ley * 100 / 31.1035) * $cot;
        if ($tipo == TipoMaterial::Concentrado OR $tipo == TipoMaterial::Guiamina) {
            if ($ley < 20.00)
                return 0;

            $obj = new ValorPorToneladaController();
            $valor = $cienPorCien * $obj->getPagablePlata($ley, $fecha) / 100;
            if($tipo == TipoMaterial::Guiamina)
                $valor = $valor - 10;
        } else {
            if ($ley < 5.00 or $ley >= 20.00)
                return 0;

            $costos = CostosPlata::sum('monto');
            $valor = ($cienPorCien * 0.70) - ($cienPorCien * 0.024) - $costos;
        }
        return $valor;
    }

    private function getValorPorTonEstano($ley, $fecha)
    {

        $tablaAcop = TablaAcopiadora::whereEsSeleccionado(true)->first();

        if (is_null($tablaAcop))
            return -1;

        $cotizacion = $this->getCotizacionDiaria(4, $fecha) / 2204.6223;

        //restando el margen a la cotizacion
        $cotizacion = $cotizacion + ($tablaAcop->margen);
        $cotizacion = round($cotizacion, 2);

        $tad = $tablaAcop->tablaAcopiadoraDetalles()->where('cotizacion', $cotizacion)->first();

        if (is_null($tad))
            return -1;
        if($ley>=5.00 and $ley<=10.00){
            return $this->getEstanioMenor10($ley, $tad);
        }
        //si es decimal
        $whole = floor($ley);      // 1
        $fraction = $ley - $whole; // .25
        if ($fraction == 0.00)
            $ley = $whole;

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

        $valor = $valorPorTonelada - ($valorPorTonelada * 0.12) - 230;
        if ($valor < 0.00)
            $valor = 0.00;
        return round($valor, 2);

    }
    private function getEstanioMenor10($ley, $tad){
        $ley10=10.00;
        $ley5=5.00;
        $valorPorTonelada10 = $tad["l_{$ley10}"];;
        $valor10 = $valorPorTonelada10 - ($valorPorTonelada10 * 0.12) - 230;
        $valor5 = $tad["l_{$ley5}"];;

        $valorPorTonelada = $valor5+ (($valor10 - $valor5) / (5.00)) * ($ley - 5.00);
        $valorPorTonelada = $valorPorTonelada * 1.4;
        return round($valorPorTonelada, 2);
    }
    private function getLeyInferior($ley)
    {
        $ley = floor($ley);
        if ((int)$ley % 5 === 0)
            return (int)$ley;
        else
            return $this->getLeyInferior($ley - 1);
    }

    private function getLeySuperior($ley)
    {
        $ley = ceil($ley);
        if ((int)$ley % 5 === 0)
            return (int)$ley;
        else
            return $this->getLeySuperior($ley + 1);
    }

    private function getValorPorTonPlomo($leyAg, $leyPb, $transporte, $tipoMaterial, $fecha)
    {
        if (($leyPb + $leyAg) < 30)
            return null;
        $contrato = Contrato::whereProductoId(2)->first();

        $plomo = $this->getCotizacionDiaria(2, $fecha);//2204.6223;
        $plata = $this->getCotizacionDiaria(1, $fecha);
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
        $transporteInterno = $contrato->transporte_interno;
        $transporteExportacion = $contrato->transporte_exportacion;
        $laboratorioInterno = $contrato->laboratorio_interno;
        $bonoRefrigerio = $contrato->bono_refrigerio;
        $publicidad = $contrato->publicidad;


        $resultadoPb1 = ($porcentajePagablePb / 100) * $leyPb;
        $resultadoPb2 = $leyPb + $deduccionUnitariaPb;
        $minPagablePb = $resultadoPb1;
        if ($resultadoPb2 < $resultadoPb1)
            $minPagablePb = $resultadoPb2;

        $pesoBruto = 1000;
        $pesoNetoSeco = 1000;

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

//

        $tipoCambio = TipoCambio::orderByDesc('fecha')->first();
        $oficialPb = CotizacionOficial::whereMineralId(2)->orderByDesc('fecha')->first();
        $oficialAg = CotizacionOficial::whereMineralId(1)->orderByDesc('fecha')->first();
        $valorBrutoAg = ((float)($leyAg * 100) / 31.1035) * (float)$oficialAg->monto * (float)$tipoCambio->dolar_venta;

        $valorBrutoPb = (float)1000 * (float)($leyPb / 100) * (float)2.2046223 *
            (float)$oficialPb->monto * (float)$tipoCambio->dolar_venta;


        $alicuotaInternaPb = $oficialPb->alicuota_interna;
        $alicuotaExportacionPb = $oficialPb->alicuota_exportacion;

        $alicuotaInternaAg = $oficialAg->alicuota_interna;
        $alicuotaExportacionAg = $oficialAg->alicuota_exportacion;

        $regaliaExternaPb = ((($alicuotaExportacionPb - $alicuotaInternaPb) / 100) * $valorBrutoPb / $tipoCambio->dolar_venta);
        $regaliaExternaAg = ((($alicuotaExportacionAg - $alicuotaInternaAg) / 100) * $valorBrutoAg / $tipoCambio->dolar_venta);

        //

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

        if ($tipoMaterial == TipoMaterial::Guiamina)
            $operacion = $operacion - 10;
        return $operacion;
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

    private function getValorPorTonZinc($leyAg, $leyZn, $transporte, $tipoMaterial, $fecha,$pesoBruto=null, $pesoSeco=null)
    {
        $contrato = Contrato::whereProductoId(1)->first();

        $zinc = $this->getCotizacionDiaria(3, $fecha);//2204.6223;
        $plata = $this->getCotizacionDiaria(1, $fecha);
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
        $transporteInterno = $contrato->transporte_interno;
        $transporteExportacion = $contrato->transporte_exportacion;
        $laboratorioInterno = $contrato->laboratorio_interno;
        $bonoRefrigerio = $contrato->bono_refrigerio;
        $publicidad = $contrato->publicidad;

        $operacionBaseEscalador = 0;
        if ($zinc > $base)
            $operacionBaseEscalador = (($zinc - $base) * $escalador);

        $resultadoZn1 = ($porcentajePagableZn / 100) * $leyZn;
        $resultadoZn2 = $leyZn + $deduccionUnitariaZn;
        $minPagableZn = $resultadoZn1;
        if ($resultadoZn2 < $resultadoZn1)
            $minPagableZn = $resultadoZn2;


        $tipoCambio = TipoCambio::orderByDesc('fecha')->first();
        $oficialZn = CotizacionOficial::whereMineralId(3)->where('fecha', '<=', $fecha)->orderByDesc('fecha')->first();
        $oficialAg = CotizacionOficial::whereMineralId(1)->where('fecha', '<=', $fecha)->orderByDesc('fecha')->first();
        $valorBrutoAg = ((float)($leyAg * 100) / 31.1035) * (float)$oficialAg->monto * (float)$tipoCambio->dolar_venta;

        $valorBrutoZn = (float)1000 * (float)($leyZn / 100) * (float)2.2046223 *
            (float)$oficialZn->monto * (float)$tipoCambio->dolar_venta;


        $alicuotaInternaZn = $oficialZn->alicuota_interna;
        $alicuotaExportacionZn = $oficialZn->alicuota_exportacion;

        $alicuotaInternaAg = $oficialAg->alicuota_interna;
        $alicuotaExportacionAg = $oficialAg->alicuota_exportacion;

        $regaliaExternaZn = ((($alicuotaExportacionZn - $alicuotaInternaZn) / 100) * $valorBrutoZn / $tipoCambio->dolar_venta);
        $regaliaExternaAg = ((($alicuotaExportacionAg - $alicuotaInternaAg) / 100) * $valorBrutoAg / $tipoCambio->dolar_venta);

        if(is_null($pesoBruto)){
            $pesoBruto=1000;
            $pesoSeco=1000;
        }


        if(($leyZn + $leyAg)>=60)
            $margen=9;

        else{
        $inferior=MargenTermino::where('ley','<=', $leyZn + $leyAg)->orderByDesc('ley')->first();
        $margenInferior = $inferior->margen_zn;
        $leyInferior = $inferior->ley;

        $superior=MargenTermino::where('ley','>=', $leyZn + $leyAg)->orderBy('ley')->first();
        $leySuperior = $superior->ley;
        $margenSuperior = $superior->margen_zn;


        if($margenInferior ==$margenSuperior)
            $margen=$margenInferior;

        else
            $margen = $margenInferior + (($margenSuperior - $margenInferior ) / ($leySuperior - $leyInferior)) * (($leyZn + $leyAg) - $leyInferior);

        }
        $margen = $margen/100;

        $operacion =
            (
                ( (
                        round($minPagableZn * $zinc / 100, 2)
                        + round($plata * ($porcentajePagableAg / 100) * round(((($leyAg * 100) / 31.1035) + $deduccionUnitariaAg), 3), 2)
                    )
                    - $maquila - $operacionBaseEscalador - ($rollBack*(($pesoBruto/1000)/($pesoSeco/1000)))) * (1-$margen)
                -($bonoCliente+$bonoProductor+$bonoEquipamiento+ $bonoEpp)
                -(($transporteExportacion + $transporteInterno)*(($pesoBruto/1000)/($pesoSeco/1000)))
                - (($laboratorioInterno + $bonoRefrigerio + $publicidad)/($pesoSeco/1000))
                -$regaliaExternaAg - $regaliaExternaZn
            ) ;

        if($tipoMaterial == TipoMaterial::Guiamina)
            $operacion = $operacion - 10;

        return $operacion;
    }
    private function getValorPorTonAntimonio($leySb, $tipo, $fecha)
    {

        $antimonio = $this->getCotizacionDiaria(6, $fecha);
        $porcentaje=0;
        if($leySb >=10.00 and $leySb<=19.99)
            $porcentaje = 0.10;
        elseif($leySb >=20.00 and $leySb<=29.99)
            $porcentaje = 0.24;
        elseif($leySb >=30.00 and $leySb<=49.99)
            $porcentaje = 0.30;
        elseif($leySb >=50.00 and $leySb<=59.99)
            $porcentaje = 0.44;
        elseif($leySb >=60.00 and $leySb<=70.00)
            $porcentaje = 0.54;
        elseif($leySb >=5 and $leySb<=5.99)
            $porcentaje = 0.05;
        elseif($leySb >=6 and $leySb<=6.99)
            $porcentaje = 0.06;
        elseif($leySb >=7 and $leySb<=7.99)
            $porcentaje = 0.07;
        elseif($leySb >=8 and $leySb<=8.99)
            $porcentaje = 0.08;
        elseif($leySb >=9 and $leySb<=9.99)
            $porcentaje = 0.09;

        $leySb = $leySb/100;
        $valor=($leySb * $porcentaje * $antimonio);
        if($tipo==TipoMaterial::Guiamina)
            $valor = $valor - 10;
        return $valor;
    }
    private function getValorPorTonCobre($leyCu, $tipoMaterial, $fecha)
    {
        $contrato = Contrato::whereProductoId(7)->first();

        $cobre = $this->getCotizacionDiaria(5, $fecha);
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
        $transporteInterno = $contrato->transporte_interno;
        $transporteExportacion = $contrato->transporte_exportacion;
        $laboratorioInterno = $contrato->laboratorio_interno;
        $bonoRefrigerio = $contrato->bono_refrigerio;
        $publicidad = $contrato->publicidad;


        $resultadoCu1 = ($porcentajePagableCu / 100) * $leyCu;
        $resultadoCu2 = $leyCu + $deduccionUnitariaCu;
        $minPagableCu = $resultadoCu1;
        if ($resultadoCu2 < $resultadoCu1)
            $minPagableCu = $resultadoCu2;

        $pesoBruto = 1000;
        $pesoNetoSeco = 1000;

        if (($leyCu) >= 60)
            $margen = 9;

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

        $tipoCambio = TipoCambio::orderByDesc('fecha')->first();
        $oficialCu = CotizacionOficial::whereMineralId(5)->orderByDesc('fecha')->first();

        $valorBrutoCu = (float)1000 * (float)($leyCu / 100) * (float)2.2046223 *
            (float)$oficialCu->monto * (float)$tipoCambio->dolar_venta;


        $alicuotaInternaCu = $oficialCu->alicuota_interna;
        $alicuotaExportacionCu = $oficialCu->alicuota_exportacion;



        $regaliaExternaCu = ((($alicuotaExportacionCu - $alicuotaInternaCu) / 100) * $valorBrutoCu / $tipoCambio->dolar_venta);

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

        if ($tipoMaterial == TipoMaterial::Guiamina)
            $operacion = $operacion - 10;
        return $operacion;
    }

    private function getCotizacionDiaria($mineralId, $fecha)
    {
        $cotizacion = CotizacionDiaria::whereMineralId($mineralId)->whereFecha($fecha)->first();
        if (!$cotizacion)
            $cotizacion = CotizacionDiaria::whereMineralId($mineralId)->orderByDesc('fecha')->first();
        $valor = $cotizacion->monto;
        if ($cotizacion->unidad == UnidadCotizacion::LF and ($mineralId != 1) and ($mineralId != 4))
            $valor = $cotizacion->monto * 2204.6223;
        return $valor;
    }

    public function getDiaria($mineralId, $fecha)
    {
        $existeFecha=true;
        $cotizacion = CotizacionDiaria::whereMineralId($mineralId)->whereFecha($fecha)->first();
        if (!$cotizacion){
            $cotizacion = CotizacionDiaria::whereMineralId($mineralId)->orderByDesc('fecha')->first();
            $existeFecha = false;
        }

        $valor = number_format($cotizacion->monto_form, 2) . ' ' . $cotizacion->unidad_form;

        return array("valor" => $valor, "existe" => $existeFecha);
    }

    public function getHumedadKg($pesoNetoHumedo, $humedad)
    {
        return $pesoNetoHumedo * ($humedad / 100);
    }

    public function getMermaKg($pesoNetoHumedo, $merma, $humedad)
    {
        return ($pesoNetoHumedo - $this->getHumedadKg($pesoNetoHumedo, $humedad)) * ($merma / 100);
    }

    private function getPesoNetoSeco($pesoNetoHumedo, $merma, $humedad)
    {
        return ($pesoNetoHumedo - $this->getHumedadKg($pesoNetoHumedo, $humedad) - $this->getMermaKg($pesoNetoHumedo, $merma, $humedad));
    }

    private function getValorNetoVenta($pesoNetoSeco, $valorPorTonelada, $tipoCambio)
    {
        return ($pesoNetoSeco / 1000) * $valorPorTonelada * $tipoCambio->dolar_compra;
    }

    private function armarLeyes($productoId, $leyAg, $leyZn, $leySn, $leyPb, $leyCu, $leySb)
    {
        if ($productoId == 1) {
            $leyes = [
                'Ag' => $leyAg,
                'Zn' => $leyZn,
            ];
            $minerales = [1, 3];
        } elseif ($productoId == 2) {
            $leyes = [
                'Ag' => $leyAg,
                'Pb' => $leyPb,
            ];
            $minerales = [1, 2];
        } elseif ($productoId == 3) {
            $leyes = [
                'Ag' => $leyAg,
                'Pb' => $leyPb,
                'Zn' => $leyZn,
            ];
            $minerales = [1, 2, 3];
        }
        elseif ($productoId == 5) {
            $leyes = [
                'Sb' => $leySb,
            ];
            $minerales = [6];
        }
        elseif ($productoId == 6) {
            $leyes = [
                'Ag' => $leyAg,
            ];
            $minerales = [1];
        }
        elseif ($productoId == 7) {
            $leyes = [
                'Cu' => $leyCu,
            ];
            $minerales = [5];
        }
        else {
            $leyes = [
                'Sn' => $leySn,
            ];
            $minerales = [4];
        }

        return array("leyes" => $leyes, "minerales" => $minerales);
    }

    public function imprimir(Request $request)
    {
        $productoId = $request->productoId;
        $pesoBruto = floatval(str_replace(',','.', $request->pesoBruto));
        $sacos = $request->sacos;
        $nombre = $request->productor;
        $humedad = floatval(str_replace(',','.', $request->humedad));
        $leyAg = floatval(str_replace(',','.', $request->leyAg));
        $leyPb = floatval(str_replace(',','.', $request->leyPb));
        $leyZn = floatval(str_replace(',','.', $request->leyZn));
        $leySn = floatval(str_replace(',','.', $request->leySn));
        $leyCu = floatval(str_replace(',','.', $request->leyCu));
        $leySb = floatval(str_replace(',','.', $request->leySb));
        $tipo = $request->tipoMaterial;
        $presentacion = $request->presentacion;
        $retenciones = floatval(str_replace(',','.', $request->retenciones));
        $transporte = 30;
        $producto = Producto::find($productoId);

        $merma =0;
        $tara =0;

        if($productoId==1 OR $productoId==2 OR $productoId==5 OR $productoId==7 OR
            ($productoId==6 and $tipo ==TipoMaterial::Broza) OR ($productoId==6 and $presentacion ==Empaque::AGranel))
            {
            $merma = 1;
        }

        if($productoId==6 AND $presentacion ==Empaque::Ensacado){
            $tara = $sacos * 0.225;
        }
        if($productoId==4){
            $tara = $sacos * 0.250;
        }

        $pesoNetoHumedo = $pesoBruto - $tara;

        $pesoNetoSeco = $this->getPesoNetoSeco($pesoNetoHumedo, $merma, $humedad);

        $leyes = $this->armarLeyes($productoId, $leyAg, $leyZn, $leySn, $leyPb, $leyCu, $leySb)["leyes"];

        $tipoCambio = TipoCambio::orderByDesc('fecha')->first();
        $minerales = $this->armarLeyes($productoId, $leyAg, $leyZn, $leySn, $leyPb, $leyCu, $leySb)["minerales"];
        $cotizacionesDiarias = CotizacionDiaria::whereFecha(date('Y-m-d'))->whereIn('mineral_id', $minerales)
            ->orderBy('mineral_id')->get();

        if (isEmpty($cotizacionesDiarias)) {
            $ultimaFecha = CotizacionDiaria::orderByDesc('fecha')->first();
            $ultimaFecha = $ultimaFecha->fecha;
            $cotizacionesDiarias = CotizacionDiaria::whereFecha($ultimaFecha)->whereIn('mineral_id', $minerales)
                ->orderBy('mineral_id')->get();
        }

        $fechaOficial = CotizacionOficial::where('fecha', '<=', date('Y-m-d'))->whereEsAprobado(true)->orderByDesc('fecha')->first();

        $cotizacionesOficiales = CotizacionOficial::whereFecha($fechaOficial->fecha)->whereEsAprobado(true)->whereIn('mineral_id', $minerales)->orderBy('mineral_id')->get();

        $valorPorTonelada = $this->getValorPorToneladaProforma($leyAg, $leyZn, $leyPb, $leySn, $leyCu, $leySb, $productoId, $transporte, $tipo, $pesoBruto, $pesoNetoSeco);

        $valorNetoVenta = $this->getValorNetoVenta($pesoNetoSeco, $valorPorTonelada, $tipoCambio);
        $totalRetenciones = ($retenciones / 100) * $valorNetoVenta;

        $regalias = $this->getMineralesRegalia($minerales, $leyes, $pesoNetoSeco, $fechaOficial, $tipoCambio);
        $totalRegalias = 0;
        $sumaBrutoVenta = 0;

        foreach ($regalias as $regalia) {
            $totalRegalias = $totalRegalias + $regalia['sub_total'];
            $sumaBrutoVenta = $regalia['valor_bruto_venta'] + $sumaBrutoVenta;
        }

        $total = $valorNetoVenta - ($totalRetenciones + $totalRegalias);
        $formatter = new NumeroALetras();

        $literal = $formatter->toMoney(abs($total), 2, 'BOLIVIANOS', 'CENTAVOS');

        $humedadKg = $this->getHumedadKg($pesoNetoHumedo, $humedad);
        $mermaKg = $this->getMermaKg($pesoNetoHumedo, $merma, $humedad);
        //$totalLiquidacion=$pagable-$formularioLiquidacion->totales['total_anticipos'];
        $vistaurl = "cotizaciones_clientes.impresion";
        $view = \View::make($vistaurl, compact('total', 'producto', 'pesoNetoHumedo', 'pesoNetoSeco', 'tipoCambio',
            'valorPorTonelada', 'valorNetoVenta', 'nombre', 'literal', 'leyes', 'cotizacionesDiarias', 'cotizacionesOficiales'
            , 'regalias', 'totalRegalias', 'retenciones', 'totalRetenciones', 'pesoBruto', 'tara', 'humedad', 'humedadKg',
            'merma', 'mermaKg', 'sumaBrutoVenta'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $canvas->page_text(350, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . date('d/m/Y H:i'), null, 7, array(0, 0, 0));

        return $pdf->stream('proforma.pdf');
    }

    private function getMineralesRegalia($mineralesIds, $leyes, $pesoNetoSeco, $fechaOficial, $tipoCambio)
    {
        $minerales = Material::whereIn('id', $mineralesIds)->get();
        $regalias = [];
        $totalRegalias = 0;
        foreach ($minerales as $mineral) {
            $lab = $leyes[$mineral->simbolo];
            $ley = is_null($lab) ? 0 : ($lab);
            $oficial = CotizacionOficial::whereFecha($fechaOficial->fecha)
                ->whereEsAprobado(true)->where('mineral_id', $mineral->id)
                ->first();

            if ($mineral->simbolo === 'Ag') {
                $valorBrutoVenta = (
                        (
                            ((float)$pesoNetoSeco / 1000) *
                            (float)($ley * 100)
                        ) / 31.1035
                    ) *
                    (float)$oficial->monto * //cotizacion oficial
                    (float)$tipoCambio->dolar_venta;
            }
            elseif ($mineral->simbolo === 'Sb') {
                    $valorBrutoVenta =
                        (float)$pesoNetoSeco *
                        (float)($ley / 100) *
                        (float)$oficial->monto / 1000 * //cotizacion oficial
                        (float)$tipoCambio->dolar_venta;
            }
            else {
                $valorBrutoVenta =
                    (float)$pesoNetoSeco *
                    (float)($ley / 100) *
                    (float)2.2046223 *
                    (float)$oficial->monto * //cotizacion oficial
                    (float)$tipoCambio->dolar_venta;
            }
            $regalias[] = [
                'simbolo' => $mineral->simbolo,
                'unidad' => $oficial->unidad,
                'ley' => $ley,
                'cotizacion_oficial' => $oficial->monto,
                'valor_bruto_venta' => $valorBrutoVenta,
                'alicuota_interna' => $oficial->alicuota_interna,
                'peso_fino' => ($mineral->simbolo == 'Ag' or  $mineral->simbolo == 'Au')? (($ley / 10000) * ($pesoNetoSeco)) : (($ley / 100) * ($pesoNetoSeco)),
                'sub_total' => $valorBrutoVenta * ($oficial->alicuota_interna / 100),
            ];
        }
        return $regalias;
    }

    private function getValorPorToneladaProforma($leyAg, $leyZn, $leyPb, $leySn, $leyCu, $leySb, $productoId, $transporte, $tipo, $pesoBruto, $pesoNetoSeco)
    {
        $fecha = date('Y-m-d');
        $valorPorTonelada = '';

        if ($productoId == '4') {
            $valorPorTonelada = $this->getValorPorTonEstano($leySn, $fecha);
        } elseif ($productoId == '1') {
            $valorPorTonelada = $this->getValorPorTonZinc($leyAg, $leyZn, $transporte, $tipo, $fecha, $pesoBruto, $pesoNetoSeco);
        } elseif ($productoId == '2') {
            $valorPorTonelada = $this->getValorPorTonPlomo($leyAg, $leyPb, $transporte, $tipo, $fecha);
        } elseif ($productoId == '5') {
            $valorPorTonelada = $this->getValorPorTonAntimonio($leySb, $tipo, $fecha);
        } elseif ($productoId == '6') {
            $valorPorTonelada = $this->getValorPorTonPlata($leyAg, $tipo, $fecha);
        }
        elseif ($productoId == '7') {
            $valorPorTonelada = $this->getValorPorTonCobre($leyCu, $tipo, $fecha);
        }
        return $valorPorTonelada;
    }

    public function getCotizaciones()
    {
        $cotizaciones = CotizacionDiaria::
        join('mineral', 'cotizacion_diaria.mineral_id', '=', 'mineral.id')
            ->whereFecha(date('Y-m-d'))
            ->get();
        if (isEmpty($cotizaciones)) {

            $ultimaFecha = CotizacionDiaria::orderByDesc('fecha')->first();
            $ultimaFecha = $ultimaFecha->fecha;
            $cotizaciones = CotizacionDiaria::
            join('mineral', 'cotizacion_diaria.mineral_id', '=', 'mineral.id')
                ->whereFecha($ultimaFecha)
                ->get();
        }

        $cotizacionesDiarias = date('d/m/Y', strtotime($cotizaciones[0]->fecha)) . "\n\n";

        foreach ($cotizaciones as $cotizacion) {
            $cotizacionesDiarias =
                (sprintf("%s %s: %s %s \n", $cotizacionesDiarias, $cotizacion->nombre, round($cotizacion->monto_form, 2), $cotizacion->unidad_form));
            //$cotizacionesDiarias . ' ' . $cotizacion->nombre .': '. $cotizacion->monto.' '. $cotizacion->unidad. nl2br("\n");
        }

        $ultimaFechaOficial = CotizacionOficial::whereEsAprobado(true)->orderByDesc('fecha')->first();
        $ultimaFechaOficial = $ultimaFechaOficial->fecha;
        $cotizaciones = CotizacionOficial::
        join('mineral', 'cotizacion_oficial.mineral_id', '=', 'mineral.id')
            ->whereEsAprobado(true)
            ->whereFecha($ultimaFechaOficial)
            ->get();
        $cotizacionesOficiales = date('d/m/Y', strtotime($cotizaciones[0]->fecha)) . "\n\n";
        foreach ($cotizaciones as $cotizacion) {
            $cotizacionesOficiales =
                (sprintf("%s %s: %s %s \n", $cotizacionesOficiales, $cotizacion->nombre, $cotizacion->monto, $cotizacion->unidad));
        }

        $acerca = "Colquechaca Mining\n\nComercializamos todo tipo de minerales.\n\nOficinas: Av. Barzola entre C. Corihuayra, C. Celestino Gutierrez y C. Heroes de la Coronilla #174.\n\nTeléfono:67200160";

        return response()->json(['diaria' => $cotizacionesDiarias, 'oficial' => $cotizacionesOficiales, 'acerca' => $acerca]);
        //return $cotizacionesDiarias;
    }

    public function getTablaEstanio(){
        $fecha = date('Y-m-d');
        $valor10=round($this->getValorPorTonEstano(10, $fecha), 2);
        $valor15=round($this->getValorPorTonEstano(15, $fecha), 2);
        $valor20=round($this->getValorPorTonEstano(20, $fecha), 2);
        $valor25=round($this->getValorPorTonEstano(25, $fecha), 2);
        $valor30=round($this->getValorPorTonEstano(30, $fecha), 2);
        $valor35=round($this->getValorPorTonEstano(35, $fecha), 2);
        $valor40=round($this->getValorPorTonEstano(40, $fecha), 2);
        $valor50=round($this->getValorPorTonEstano(50, $fecha), 2);
        $valor60=round($this->getValorPorTonEstano(60, $fecha), 2);
        $valor70=round($this->getValorPorTonEstano(70, $fecha), 2);
        $diaria=$this->getDiaria(4, $fecha)["valor"];

        $vistaurl = "cotizaciones_clientes.tabla_estanio";
        $view = \View::make($vistaurl, compact('diaria', 'valor10', 'valor15', 'valor20', 'valor25', 'valor30', 'valor35',
                'valor40', 'valor50', 'valor60', 'valor70'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $canvas->page_text(350, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . date('d/m/Y H:i'), null, 7, array(0, 0, 0));

        return $pdf->stream('tablaEstaño.pdf');
    }

    public function getTablaPlomoPlata(){
        $fecha = date('Y-m-d');
        $ag5pb35 = round($this->getValorPorTonPlomo(5, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5pb40 = round($this->getValorPorTonPlomo(5, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5pb45 = round($this->getValorPorTonPlomo(5, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5pb50 = round($this->getValorPorTonPlomo(5, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5pb55 = round($this->getValorPorTonPlomo(5, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5pb60 = round($this->getValorPorTonPlomo(5, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5pb65 = round($this->getValorPorTonPlomo(5, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5pb70 = round($this->getValorPorTonPlomo(5, 70, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag10pb35 = round($this->getValorPorTonPlomo(10, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10pb40 = round($this->getValorPorTonPlomo(10, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10pb45 = round($this->getValorPorTonPlomo(10, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10pb50 = round($this->getValorPorTonPlomo(10, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10pb55 = round($this->getValorPorTonPlomo(10, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10pb60 = round($this->getValorPorTonPlomo(10, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10pb65 = round($this->getValorPorTonPlomo(10, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10pb70 = round($this->getValorPorTonPlomo(10, 70, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag15pb35 = round($this->getValorPorTonPlomo(15, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag15pb40 = round($this->getValorPorTonPlomo(15, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag15pb45 = round($this->getValorPorTonPlomo(15, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag15pb50 = round($this->getValorPorTonPlomo(15, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag15pb55 = round($this->getValorPorTonPlomo(15, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag15pb60 = round($this->getValorPorTonPlomo(15, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag15pb65 = round($this->getValorPorTonPlomo(15, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag15pb70 = round($this->getValorPorTonPlomo(15, 70, 30, TipoMaterial::Concentrado, $fecha), 2);


        $ag20pb35 = round($this->getValorPorTonPlomo(20, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag20pb40 = round($this->getValorPorTonPlomo(20, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag20pb45 = round($this->getValorPorTonPlomo(20, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag20pb50 = round($this->getValorPorTonPlomo(20, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag20pb55 = round($this->getValorPorTonPlomo(20, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag20pb60 = round($this->getValorPorTonPlomo(20, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag20pb65 = round($this->getValorPorTonPlomo(20, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag20pb70 = round($this->getValorPorTonPlomo(20, 70, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag25pb35 = round($this->getValorPorTonPlomo(25, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag25pb40 = round($this->getValorPorTonPlomo(25, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag25pb45 = round($this->getValorPorTonPlomo(25, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag25pb50 = round($this->getValorPorTonPlomo(25, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag25pb55 = round($this->getValorPorTonPlomo(25, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag25pb60 = round($this->getValorPorTonPlomo(25, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag25pb65 = round($this->getValorPorTonPlomo(25, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag25pb70 = round($this->getValorPorTonPlomo(25, 70, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag30pb35 = round($this->getValorPorTonPlomo(30, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag30pb40 = round($this->getValorPorTonPlomo(30, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag30pb45 = round($this->getValorPorTonPlomo(30, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag30pb50 = round($this->getValorPorTonPlomo(30, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag30pb55 = round($this->getValorPorTonPlomo(30, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag30pb60 = round($this->getValorPorTonPlomo(30, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag30pb65 = round($this->getValorPorTonPlomo(30, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag30pb70 = round($this->getValorPorTonPlomo(30, 70, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag35pb35 = round($this->getValorPorTonPlomo(35, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag35pb40 = round($this->getValorPorTonPlomo(35, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag35pb45 = round($this->getValorPorTonPlomo(35, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag35pb50 = round($this->getValorPorTonPlomo(35, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag35pb55 = round($this->getValorPorTonPlomo(35, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag35pb60 = round($this->getValorPorTonPlomo(35, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag35pb65 = round($this->getValorPorTonPlomo(35, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag35pb70 = round($this->getValorPorTonPlomo(35, 70, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag40pb35 = round($this->getValorPorTonPlomo(40, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag40pb40 = round($this->getValorPorTonPlomo(40, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag40pb45 = round($this->getValorPorTonPlomo(40, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag40pb50 = round($this->getValorPorTonPlomo(40, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag40pb55 = round($this->getValorPorTonPlomo(40, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag40pb60 = round($this->getValorPorTonPlomo(40, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag40pb65 = round($this->getValorPorTonPlomo(40, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag40pb70 = round($this->getValorPorTonPlomo(40, 70, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag45pb35 = round($this->getValorPorTonPlomo(45, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag45pb40 = round($this->getValorPorTonPlomo(45, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag45pb45 = round($this->getValorPorTonPlomo(45, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag45pb50 = round($this->getValorPorTonPlomo(45, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag45pb55 = round($this->getValorPorTonPlomo(45, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag45pb60 = round($this->getValorPorTonPlomo(45, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag45pb65 = round($this->getValorPorTonPlomo(45, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag45pb70 = round($this->getValorPorTonPlomo(45, 70, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag50pb35 = round($this->getValorPorTonPlomo(50, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag50pb40 = round($this->getValorPorTonPlomo(50, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag50pb45 = round($this->getValorPorTonPlomo(50, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag50pb50 = round($this->getValorPorTonPlomo(50, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag50pb55 = round($this->getValorPorTonPlomo(50, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag50pb60 = round($this->getValorPorTonPlomo(50, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag50pb65 = round($this->getValorPorTonPlomo(50, 65, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag50pb70 = round($this->getValorPorTonPlomo(50, 70, 30, TipoMaterial::Concentrado, $fecha), 2);


        $diariaAg=$this->getDiaria(1, $fecha)["valor"];
        $diariaPb=$this->getDiaria(2, $fecha)["valor"];
        $oficialPb = CotizacionOficial::whereMineralId(2)->whereEsAprobado(true)->orderByDesc('fecha')->first();
        $oficialAg = CotizacionOficial::whereMineralId(1)->whereEsAprobado(true)->orderByDesc('fecha')->first();

        $vistaurl = "cotizaciones_clientes.tabla_plomo_plata";
        $view = \View::make($vistaurl, compact(
            'ag5pb35', 'ag5pb40','ag5pb45', 'ag5pb50', 'ag5pb55',  'ag5pb60', 'ag5pb65', 'ag5pb70',
            'ag10pb35', 'ag10pb40','ag10pb45', 'ag10pb50', 'ag10pb55',  'ag10pb60', 'ag10pb65', 'ag10pb70',
            'ag15pb35', 'ag15pb40','ag15pb45', 'ag15pb50', 'ag15pb55',  'ag15pb60', 'ag15pb65', 'ag15pb70',
            'ag20pb35', 'ag20pb40','ag20pb45', 'ag20pb50', 'ag20pb55',  'ag20pb60', 'ag20pb65', 'ag20pb70',
            'ag25pb35', 'ag25pb40','ag25pb45', 'ag25pb50', 'ag25pb55',  'ag25pb60', 'ag25pb65', 'ag25pb70',
            'ag30pb35', 'ag30pb40','ag30pb45', 'ag30pb50', 'ag30pb55',  'ag30pb60', 'ag30pb65', 'ag30pb70',
            'ag35pb35', 'ag35pb40','ag35pb45', 'ag35pb50', 'ag35pb55',  'ag35pb60', 'ag35pb65', 'ag35pb70',
            'ag40pb35', 'ag40pb40','ag40pb45', 'ag40pb50', 'ag40pb55',  'ag40pb60', 'ag40pb65', 'ag40pb70',
            'ag45pb35', 'ag45pb40','ag45pb45', 'ag45pb50', 'ag45pb55',  'ag45pb60', 'ag45pb65', 'ag45pb70',
            'ag50pb35', 'ag50pb40','ag50pb45', 'ag50pb50', 'ag50pb55',  'ag50pb60', 'ag50pb65', 'ag50pb70',
            'diariaAg', 'diariaPb', 'oficialAg', 'oficialPb'

        ))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $canvas->page_text(350, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . date('d/m/Y H:i'), null, 7, array(0, 0, 0));

        return $pdf->stream('tablaPlomoPlata.pdf');
    }



    public function getTablaZincPlata(){
        $fecha = date('Y-m-d');
        $ag2zn35 = round($this->getValorPorTonZinc(2, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag2zn40 = round($this->getValorPorTonZinc(2, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag2zn45 = round($this->getValorPorTonZinc(2, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag2zn50 = round($this->getValorPorTonZinc(2, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag2zn55 = round($this->getValorPorTonZinc(2, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag2zn60 = round($this->getValorPorTonZinc(2, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag2zn65 = round($this->getValorPorTonZinc(2, 65, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag3zn35 = round($this->getValorPorTonZinc(3, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag3zn40 = round($this->getValorPorTonZinc(3, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag3zn45 = round($this->getValorPorTonZinc(3, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag3zn50 = round($this->getValorPorTonZinc(3, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag3zn55 = round($this->getValorPorTonZinc(3, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag3zn60 = round($this->getValorPorTonZinc(3, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag3zn65 = round($this->getValorPorTonZinc(3, 65, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag4zn35 = round($this->getValorPorTonZinc(4, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag4zn40 = round($this->getValorPorTonZinc(4, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag4zn45 = round($this->getValorPorTonZinc(4, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag4zn50 = round($this->getValorPorTonZinc(4, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag4zn55 = round($this->getValorPorTonZinc(4, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag4zn60 = round($this->getValorPorTonZinc(4, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag4zn65 = round($this->getValorPorTonZinc(4, 65, 30, TipoMaterial::Concentrado, $fecha), 2);


        $ag5zn35 = round($this->getValorPorTonZinc(5, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5zn40 = round($this->getValorPorTonZinc(5, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5zn45 = round($this->getValorPorTonZinc(5, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5zn50 = round($this->getValorPorTonZinc(5, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5zn55 = round($this->getValorPorTonZinc(5, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5zn60 = round($this->getValorPorTonZinc(5, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag5zn65 = round($this->getValorPorTonZinc(5, 65, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag6zn35 = round($this->getValorPorTonZinc(6, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag6zn40 = round($this->getValorPorTonZinc(6, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag6zn45 = round($this->getValorPorTonZinc(6, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag6zn50 = round($this->getValorPorTonZinc(6, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag6zn55 = round($this->getValorPorTonZinc(6, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag6zn60 = round($this->getValorPorTonZinc(6, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag6zn65 = round($this->getValorPorTonZinc(6, 65, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag7zn35 = round($this->getValorPorTonZinc(7, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag7zn40 = round($this->getValorPorTonZinc(7, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag7zn45 = round($this->getValorPorTonZinc(7, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag7zn50 = round($this->getValorPorTonZinc(7, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag7zn55 = round($this->getValorPorTonZinc(7, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag7zn60 = round($this->getValorPorTonZinc(7, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag7zn65 = round($this->getValorPorTonZinc(7, 65, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag8zn35 = round($this->getValorPorTonZinc(8, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag8zn40 = round($this->getValorPorTonZinc(8, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag8zn45 = round($this->getValorPorTonZinc(8, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag8zn50 = round($this->getValorPorTonZinc(8, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag8zn55 = round($this->getValorPorTonZinc(8, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag8zn60 = round($this->getValorPorTonZinc(8, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag8zn65 = round($this->getValorPorTonZinc(8, 65, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag9zn35 = round($this->getValorPorTonZinc(9, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag9zn40 = round($this->getValorPorTonZinc(9, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag9zn45 = round($this->getValorPorTonZinc(9, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag9zn50 = round($this->getValorPorTonZinc(9, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag9zn55 = round($this->getValorPorTonZinc(9, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag9zn60 = round($this->getValorPorTonZinc(9, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag9zn65 = round($this->getValorPorTonZinc(9, 65, 30, TipoMaterial::Concentrado, $fecha), 2);

        $ag10zn35 = round($this->getValorPorTonZinc(10, 35, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10zn40 = round($this->getValorPorTonZinc(10, 40, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10zn45 = round($this->getValorPorTonZinc(10, 45, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10zn50 = round($this->getValorPorTonZinc(10, 50, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10zn55 = round($this->getValorPorTonZinc(10, 55, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10zn60 = round($this->getValorPorTonZinc(10, 60, 30, TipoMaterial::Concentrado, $fecha), 2);
        $ag10zn65 = round($this->getValorPorTonZinc(10, 65, 30, TipoMaterial::Concentrado, $fecha), 2);

        $diariaAg=$this->getDiaria(1, $fecha)["valor"];
        $diariaZn=$this->getDiaria(3, $fecha)["valor"];
        $oficialZn = CotizacionOficial::whereMineralId(3)->whereEsAprobado(true)->orderByDesc('fecha')->first();
        $oficialAg = CotizacionOficial::whereMineralId(1)->whereEsAprobado(true)->orderByDesc('fecha')->first();

        $vistaurl = "cotizaciones_clientes.tabla_zinc_plata";
        $view = \View::make($vistaurl, compact(
            'ag2zn35', 'ag2zn40','ag2zn45', 'ag2zn50', 'ag2zn55',  'ag2zn60', 'ag2zn65',
            'ag3zn35', 'ag3zn40','ag3zn45', 'ag3zn50', 'ag3zn55',  'ag3zn60', 'ag3zn65',
            'ag4zn35', 'ag4zn40','ag4zn45', 'ag4zn50', 'ag4zn55',  'ag4zn60', 'ag4zn65',
            'ag5zn35', 'ag5zn40','ag5zn45', 'ag5zn50', 'ag5zn55',  'ag5zn60', 'ag5zn65',
            'ag6zn35', 'ag6zn40','ag6zn45', 'ag6zn50', 'ag6zn55',  'ag6zn60', 'ag6zn65',
            'ag7zn35', 'ag7zn40','ag7zn45', 'ag7zn50', 'ag7zn55',  'ag7zn60', 'ag7zn65',
            'ag8zn35', 'ag8zn40','ag8zn45', 'ag8zn50', 'ag8zn55',  'ag8zn60', 'ag8zn65',
            'ag9zn35', 'ag9zn40','ag9zn45', 'ag9zn50', 'ag9zn55',  'ag9zn60', 'ag9zn65',
            'ag10zn35', 'ag10zn40','ag10zn45', 'ag10zn50', 'ag10zn55',  'ag10zn60', 'ag10zn65',
            'diariaAg', 'diariaZn', 'oficialAg', 'oficialZn'

        ))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $canvas->page_text(350, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . date('d/m/Y H:i'), null, 7, array(0, 0, 0));

        return $pdf->stream('tablaZincPlata.pdf');
    }
}
