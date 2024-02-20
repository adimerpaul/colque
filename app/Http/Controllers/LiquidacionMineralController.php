<?php

namespace App\Http\Controllers;

use App\Models\CotizacionDiaria;
use App\Models\CotizacionOficial;
use App\Models\FormularioLiquidacion;
use App\Models\ProductoMineral;
use App\Models\VentaFactura;
use App\Patrones\Fachada;
use App\Patrones\TipoFactura;

class LiquidacionMineralController
{
    public function getMinerales($formulario_id, $fecha)
    {
        $fecha = Fachada::setDateFormat($fecha);
        $fechaInferior = Fachada::getFechaCotizacionOficial($fecha);
        if(is_null($fechaInferior))
            return [];
      //  dd($fecha_cotizacion);
        // $fechaInferior = null;
        // $cotizacion = CotizacionOficial::orderByDesc('fecha')->where('fecha', '<=', $fecha)->first();
        // if(!is_null($cotizacion))
        //     $fechaInferior = $cotizacion->fecha;

        // $fechaSuperior = null;
        // $cotizacion = CotizacionOficial::orderBy('fecha')->where('fecha', '>=', $fecha)->first();
        // if(!is_null($cotizacion))
        //     $fechaSuperior = $cotizacion->fecha;

        // if(is_null($fechaInferior) || is_null($fechaSuperior) )
        //     return [];

        $minerales = \DB::select("select lm.id ,m.simbolo , m.nombre, lm.es_penalizacion, lm.ley_minima,
                                    co.fecha, co.monto, co.unidad, co.alicuota_exportacion , co.alicuota_interna
                                    from liquidacion_mineral lm
                                        inner join mineral m on lm.mineral_id = m.id
                                        inner join cotizacion_oficial co on co.mineral_id = m.id
                                     where lm.formulario_liquidacion_id = ? and co.fecha = ?
                                     order by m.id", [$formulario_id, $fechaInferior]);

        return $minerales;
    }

    public function getCotizacion($fecha, $idMin, $letra){
        $cotizacion = null;
        $producto = ProductoMineral::
            whereHas('producto', function ($q) use ($letra) {
                $q->whereLetra($letra);
            })
            ->whereMineralId($idMin)
            ->whereEsPenalizacion(false)
            ->count();
        if($producto>0){
            $cotizacion= CotizacionDiaria::whereMineralId($idMin)
                ->whereFecha($fecha)
                ->first();
            $cotizacion = $cotizacion->monto;
        }

        return $cotizacion;
    }

    public function calculoRegalia($ley, $leyMinima, $conLeyMinima, $valorBrutoVenta, $alicuotaInterna, $formId){
        $formulario = FormularioLiquidacion::find($formId);
        if($conLeyMinima==false)
            return ($valorBrutoVenta * ($alicuotaInterna / 100));

        $regalia=  ($ley >= $leyMinima or (!$formulario->es_quemado and $formulario->fecha_liquidacion>='2023-04-08')) ?
            ($valorBrutoVenta * ($alicuotaInterna / 100))
            : 0;


        if ($formulario->letra == 'B' and $formId !=506 ) {
            $suma=0;
            foreach($formulario->laboratorio_promedio as $lab){
                if($lab->simbolo=='Pb' OR $lab->simbolo=='Ag')
                    $suma= $suma + $lab->promedio;
            }
            if($suma <30 and $conLeyMinima==true)
                $regalia =0;

                //and $formId !=1732 and $formId !=2820 and $formId !=1943 and $formId !=2070 and $formId !=1990 and $formId !=2940 and $formId !=2941 and $formId !=3892) //hardcoding
        }
        return $regalia;
    }
    public function calculoRegaliaSinarcom($valorBrutoVenta, $conLeyMinima, $alicuotaInterna, $formId){
        $regalia=($valorBrutoVenta * ($alicuotaInterna / 100));
        $formulario = FormularioLiquidacion::find($formId);
        if ($formulario->letra == 'B' and $formId !=506 ) {
            $suma=0;
            foreach($formulario->laboratorio_promedio as $lab){
                if($lab->simbolo=='Pb' OR $lab->simbolo=='Ag')
                    $suma= $suma + $lab->promedio;
            }
            if($suma <30  and $conLeyMinima==true) // and $formId !=1732 and $formId !=2820 and $formId !=1943 and $formId !=2070 and $formId !=1990 and $formId !=2940 and $formId !=2941 and $formId !=3892) //hardcoding
                $regalia =0;
        }
        return $regalia;
    }


    public function getMineralesVenta($ventaId, $fecha, $tipo)
    {
        $venta=VentaFactura::find($ventaId);
        $fecha = Fachada::setDateFormat($fecha);
        $fechaInferior = Fachada::getFechaCotizacionOficial($fecha);
        if(is_null($fechaInferior))
            return [];

        if($tipo==TipoFactura::CompraVenta){
            $minerales = \DB::select("SELECT
                                    mineral.nandina as codigo_producto,
                                    mineral.nandina,
                                    1 as cantidad,
                                    220 as unidad_medida,
                                    concat('MINERALES DE ', UPPER(mineral.nombre), ' Y SUS CONCENTRADOS LOTE ', '$venta->lote')  as descripcion,
                                    '$venta->monto_total' as precio_unitario,
                                    '0' as monto_descuento,
                                    '$venta->monto_total' as subtotal
                                FROM mineral
                                    INNER JOIN venta_mineral ON mineral.id = venta_mineral.mineral_id
                                WHERE venta_id = ?
                                     order by mineral.id", [$ventaId]);
        }
        else{
            $minerales = \DB::select("SELECT
                                    mineral.nandina as codigo_producto,
                                    mineral.nandina,
                                    CASE mineral.simbolo

                                        WHEN 'Ag' THEN round((descripcion_leyes *'$venta->kilos_netos_secos'/1000000), 5)
                                        WHEN 'Pb' THEN round((descripcion_leyes *'$venta->kilos_netos_secos'/100), 5)
                                        WHEN 'Zn' THEN round((descripcion_leyes *'$venta->kilos_netos_secos'/100), 5)
                                    END
                                        as cantidad_extraccion,
                                    CASE cotizacion_oficial.unidad
                                        WHEN 'OT' THEN 63
                                        WHEN 'LF' THEN 64
                                    END

                                     as unidad_medida,
                                    concat('MINERALES DE ', UPPER(mineral.nombre), ' Y SUS CONCENTRADOS LOTE ', '$venta->lote')  as descripcion,
                                    cotizacion_oficial.monto as precio_unitario,
                                    descripcion_leyes,
                                    CASE cotizacion_oficial.unidad
                                        WHEN 'LF' THEN (round(('$venta->kilos_netos_secos'*descripcion_leyes/100*2.2046223), 5))
                                        WHEN 'OT' THEN (round(  (('$venta->kilos_netos_secos'/31.1035)*(descripcion_leyes/1000))  ,5)   )
                                    END

                                        as cantidad,
                                    '22' as unidad_medida_extraccion,
                                    '0' as monto_descuento,

                                    round((
                                    cotizacion_oficial.monto * (CASE cotizacion_oficial.unidad
                                        WHEN 'LF' THEN ('$venta->kilos_netos_secos'*descripcion_leyes/100*2.2046223)
                                        WHEN 'OT' THEN (('$venta->kilos_netos_secos'/31.1035)*(descripcion_leyes/1000))
                                    END)), 5) as subtotal
                                FROM mineral
                                    INNER JOIN venta_mineral ON mineral.id = venta_mineral.mineral_id
                                    INNER JOIN cotizacion_oficial ON mineral.id = cotizacion_oficial.mineral_id
                                WHERE venta_id = ? and cotizacion_oficial.fecha = ?
                                     order by mineral.id", [$ventaId, $fechaInferior]);
        }


        return $minerales;
    }


}
