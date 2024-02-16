<?php

namespace App\Http\Controllers;

use App\Models\Anticipo;
use App\Models\BasePlomoPlata;
use App\Models\Contrato;
use App\Models\CostosPlata;
use App\Models\CotizacionDiaria;
use App\Models\CotizacionOficial;
use App\Models\FormularioDescuento;
use App\Models\FormularioLiquidacion;
use App\Models\MargenTermino;
use App\Models\Material;
use App\Models\Producto;
use App\Models\TablaAcopiadora;
use App\Models\TerminosPlomoPlata;
use App\Models\TipoCambio;
use App\Patrones\TipoMaterial;
use App\Patrones\UnidadCotizacion;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CotizacionClienteController
{

    public function index(Request $request)
    {
        return view('cotizaciones_clientes.index');
    }

    public function getValorPorTonelada(Request $request)
    {
        $obj = new CotizadorAndroidController();
        $leyAg = $request->leyAg;
        $leyZn = $request->leyZn;
        $leyPb = $request->leyPb;
        $leySn = $request->leySn;
        $leyCu = $request->leyCu;
        $leySb = $request->leySb;
        $transporte = $request->transporte;
        $productoId = $request->productoId;
        $tipoMaterial = $request->tipo_material;
        $fecha = $request->fecha;

        $valorPorTonelada = '';

        if ($productoId == '4') {
            $valorPorTonelada = $this->getValorPorTonEstano($leySn, $fecha);
            $diaria = 'SN: ' . $obj->getDiaria(4, $fecha)["valor"];
            if ($valorPorTonelada === -1) { // si no se encuentra en la matriz
                return response()->json(['res' => false, 'message' => $valorPorTonelada]);
            }
        } elseif ($productoId == '1') {
            $valorPorTonelada = $this->getValorPorTonZinc($leyAg, $leyZn, $transporte, $tipoMaterial, $fecha);
            $diaria = 'ZN: ' . $obj->getDiaria(3, $fecha)["valor"] . ', AG: ' . $obj->getDiaria(1, $fecha)["valor"];
        } elseif ($productoId == '2') {
            $valorPorTonelada = $this->getValorPorTonPlomo($leyAg, $leyPb, $transporte, $tipoMaterial, $fecha);
            $diaria = 'PB: ' . $obj->getDiaria(2, $fecha)["valor"] . ', AG: ' . $obj->getDiaria(1, $fecha)["valor"];
        } elseif ($productoId == '5') {
            $valorPorTonelada = $this->getValorPorTonAntimonio($leySb, $tipoMaterial, $fecha);
            $diaria = 'SB: ' . $obj->getDiaria(6, $fecha)["valor"];
        }
        elseif ($productoId == '6') {
            $valorPorTonelada = $this->getValorPorTonPlata($leyAg, $tipoMaterial, $fecha);
            $diaria = 'AG: ' . $obj->getDiaria(1, $fecha)["valor"];
        }
        elseif ($productoId == '7') {
            $valorPorTonelada = $this->getValorPorTonCobre($leyCu, $tipoMaterial, $fecha);
            $diaria = 'CU: ' . $obj->getDiaria(5, $fecha)["valor"];
        }
        return response()->json(['res' => true, 'valor' => $valorPorTonelada, 'diaria' => $diaria]);
    }

    public function getValorPorTonPlata($ley, $tipo, $fecha)
    {
        $cot = $this->getCotizacionDiaria(1, $fecha);
        $cienPorCien = ($ley * 100 / 31.1035) * $cot;
        if ($tipo == TipoMaterial::Concentrado or $tipo == TipoMaterial::Guiamina) {
            if ($ley < 15.00)
                return 0;

            $obj = new ValorPorToneladaController();
            $valor = $cienPorCien * $obj->getPagablePlata($ley, $fecha) / 100;
            if ($tipo == TipoMaterial::Guiamina)
                $valor = $valor - 10;
        } else {
            if ($ley < 4.00 or $ley >= 20.00)
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

        if ($ley >= 5.00 and $ley <= 10.00) {
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

    private function getEstanioMenor10($ley, $tad)
    {
        $ley10 = 10.00;
        $ley5 = 5.00;
        $valorPorTonelada10 = $tad["l_{$ley10}"];;
        $valor10 = $valorPorTonelada10 - ($valorPorTonelada10 * 0.12) - 230;
        $valor5 = $tad["l_{$ley5}"];;

        $valorPorTonelada = $valor5 + (($valor10 - $valor5) / (5.00)) * ($ley - 5.00);
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

    private function getValorPorTonCobre($leyCu,  $tipoMaterial, $fecha)
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


    private function getTerminosPbAg($sumaLey)
    {
        return TerminosPlomoPlata::where('ley_minima', '<=', $sumaLey)->where('ley_maxima', '>=', $sumaLey)->first();
    }

    private function getBasePbAg($cotizacion)
    {
        $item = BasePlomoPlata::where('lme_pb_minimo', '<=', $cotizacion)->where('lme_pb_maximo', '>=', $cotizacion)->first();
        return $item->base;
    }

    private function getValorPorTonZinc($leyAg, $leyZn, $transporte, $tipoMaterial, $fecha)
    {
        if (($leyZn + $leyAg) < 35)
            return null;
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


        $resultadoZn1 = ($porcentajePagableZn / 100) * $leyZn;
        $resultadoZn2 = $leyZn + $deduccionUnitariaZn;
        $minPagableZn = $resultadoZn1;
        if ($resultadoZn2 < $resultadoZn1)
            $minPagableZn = $resultadoZn2;


        $tipoCambio = TipoCambio::orderByDesc('fecha')->first();
        $oficialZn = CotizacionOficial::whereMineralId(3)->orderByDesc('fecha')->first();
        $oficialAg = CotizacionOficial::whereMineralId(1)->orderByDesc('fecha')->first();
        $valorBrutoAg = ((float)($leyAg * 100) / 31.1035) * (float)$oficialAg->monto * (float)$tipoCambio->dolar_venta;

        $valorBrutoZn = (float)1000 * (float)($leyZn / 100) * (float)2.2046223 *
            (float)$oficialZn->monto * (float)$tipoCambio->dolar_venta;


        $alicuotaInternaZn = $oficialZn->alicuota_interna;
        $alicuotaExportacionZn = $oficialZn->alicuota_exportacion;

        $alicuotaInternaAg = $oficialAg->alicuota_interna;
        $alicuotaExportacionAg = $oficialAg->alicuota_exportacion;

        $regaliaExternaZn = ((($alicuotaExportacionZn - $alicuotaInternaZn) / 100) * $valorBrutoZn / $tipoCambio->dolar_venta);
        $regaliaExternaAg = ((($alicuotaExportacionAg - $alicuotaInternaAg) / 100) * $valorBrutoAg / $tipoCambio->dolar_venta);
        $pesoBruto = 1000;
        $pesoSeco = 1000;

        if (($leyZn + $leyAg) >= 60)
            $margen = 9;

        else {
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

        }
        $margen = $margen / 100;

        $operacionBaseEscalador = 0;
        if ($zinc > $base)
            $operacionBaseEscalador = (($zinc - $base) * $escalador);

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


//        $operacion =
//            ((round($zinc * $minPagableZn / 100, 2) + round($plata * ($porcentajePagableAg / 100) *
//                        round(((($leyAg * 100) / 31.1035) + $deduccionUnitariaAg), 3), 2))
//                - $maquila - $escalador - $laboratorio - $manipuleo - $molienda - $margenAdministrativo - $transporteAPuerto - $rollBack - $transporte
//                - $regaliaExternaAg - $regaliaExternaZn) * $margen;

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
        } elseif ($productoId == 5) {
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

//        return $leyes;
        return array("leyes" => $leyes, "minerales" => $minerales);
    }

    public function imprimir(Request $request)
    {
        $productoId = $request->productoId;
        $pesoBruto = $request->pesoBruto;
        $tara = $request->tara;
        $nombre = $request->productor;
        $merma = $request->merma;
        $humedad = $request->humedad;
        $leyAg = $request->leyAg;
        $leyPb = $request->leyPb;
        $leyZn = $request->leyZn;
        $leySn = $request->leySn;
        $leyCu = $request->leyCu;
        $leySb = $request->leySb;
        $retenciones = $request->retenciones;
        $transporte = $request->transporte;
        $tipoMaterial = $request->tipo_material;
        $producto = Producto::find($productoId);
        $pesoNetoHumedo = $pesoBruto - $tara;
        $pesoNetoSeco = $this->getPesoNetoSeco($pesoNetoHumedo, $merma, $humedad);

        $leyes = $this->armarLeyes($productoId, $leyAg, $leyZn, $leySn, $leyPb, $leyCu, $leySb)["leyes"];
        $tipoCambio = TipoCambio::whereFecha(date('Y-m-d'))->first();
        $minerales = $this->armarLeyes($productoId, $leyAg, $leyZn, $leySn, $leyPb, $leyCu, $leySb)["minerales"];
        $cotizacionesDiarias = CotizacionDiaria::whereFecha(date('Y-m-d'))->whereIn('mineral_id', $minerales)->orderBy('mineral_id')->get();

        $fechaOficial = CotizacionOficial::where('fecha', '<', date('Y-m-d'))->whereEsAprobado(true)->orderByDesc('fecha')->first();

        $cotizacionesOficiales = CotizacionOficial::whereFecha($fechaOficial->fecha)->whereIn('mineral_id', $minerales)->whereEsAprobado(true)->orderBy('mineral_id')->get();

        $valorPorTonelada = $this->getValorPorToneladaProforma($leyAg, $leyZn, $leyPb, $leySn, $leyCu, $leySb, $productoId, $transporte, $tipoMaterial);
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
            $oficial = CotizacionOficial::whereFecha($fechaOficial->fecha)->whereEsAprobado(true)->where('mineral_id', $mineral->id)->first();

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
                //'peso_fino' => ($ley / 100) * ($pesoNetoSeco),
                'peso_fino' => ($mineral->simbolo == 'Ag' or $mineral->simbolo == 'Au') ? (($ley / 10000) * ($pesoNetoSeco)) : (($ley / 100) * ($pesoNetoSeco)),
                'sub_total' => $valorBrutoVenta * ($oficial->alicuota_interna / 100),
            ];
        }
        return $regalias;
    }

    private function getValorPorToneladaProforma($leyAg, $leyZn, $leyPb, $leySn, $leyCu, $leySb, $productoId, $transporte, $tipoMaterial)
    {
        $fecha = date('Y-m-d');
        $valorPorTonelada = '';
        if ($productoId == '4') {
            $valorPorTonelada = $this->getValorPorTonEstano($leySn, $fecha);
        } elseif ($productoId == '1') {
            $valorPorTonelada = $this->getValorPorTonZinc($leyAg, $leyZn, $transporte, $tipoMaterial, $fecha);
        } elseif ($productoId == '2') {
            $valorPorTonelada = $this->getValorPorTonPlomo($leyAg, $leyPb, $transporte, $tipoMaterial, $fecha);
        } elseif ($productoId == '5') {
            $valorPorTonelada = $this->getValorPorTonAntimonio($leySb, $tipoMaterial, $fecha);
        }
        elseif ($productoId == '6') {
            $valorPorTonelada = $this->getValorPorTonPlata($leyAg, $tipoMaterial, $fecha);
        }
        elseif ($productoId == '7') {
            $valorPorTonelada = $this->getValorPorTonCobre($leyCu, $tipoMaterial, $fecha);
        }
        return $valorPorTonelada;
    }
}
