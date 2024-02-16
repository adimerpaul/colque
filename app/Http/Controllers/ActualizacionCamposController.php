<?php

namespace App\Http\Controllers;

use App\Models\Activo\ActivoFijo;
use App\Models\Anticipo;
use App\Models\Bono;
use App\Models\CampoReporte;
use App\Models\Cliente;
use App\Models\Concentrado;
use App\Models\Contrato;
use App\Models\Cooperativa;
use App\Models\CuentaCobrar;
use App\Models\DescuentoBonificacion;
use App\Models\DocumentoCompra;
use App\Models\FormularioDescuento;
use App\Models\FormularioLiquidacion;
use App\Models\Lab\Ensayo;
use App\Models\Lab\Recepcion;
use App\Models\LaboratorioEnsayo;
use App\Models\Movimiento;
use App\Models\PagoMovimiento;
use App\Models\Personal;
use App\Models\Producto;
use App\Models\Rrhh\Asistencia;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaFormularioLiquidacion;
use App\Models\VentaMineral;
use App\Patrones\ClaseDescuento;
use App\Patrones\ClaseDevolucion;
use App\Patrones\EnFuncion;
use App\Patrones\Estado;
use App\Patrones\EstadoVenta;
use App\Patrones\Fachada;
use App\Patrones\TipoLoteVenta;
use App\Patrones\UnidadDescuentoBonificacion;
use Illuminate\Http\Request;
use Response;
use DB;

class ActualizacionCamposController extends AppBaseController
{

    public function liquidoPagable()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'liquido_pagable' => $formulario->totales['total_liquidacion']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaLiquidoPagable()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->liquido_pagable, 2) != round($formulario->totales['total_liquidacion'], 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_liquido_pagable' => round($formulario->totales['total_liquidacion'], 2) . ' ___ ' . round($formulario->liquido_pagable, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_liquido_pagable' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function totalAnticipo()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'total_anticipo' => $formulario->totales['total_anticipos']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaTotalAnticipo()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->totales['total_anticipos'], 2) != round($formulario->total_anticipo, 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_total_anticipo' => round($formulario->totales['total_anticipos'], 2) . ' ___ ' . round($formulario->total_anticipo, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_total_anticipo' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function totalRetencionDescuento()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'total_retencion_descuento' =>
                        ($formulario->totales['total_retenciones'] + $formulario->totales['total_descuentos'])]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaTotalRetencionDescuento()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round(($formulario->totales['total_retenciones'] + $formulario->totales['total_descuentos']), 2) != round($formulario->total_retencion_descuento, 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_total_retencion_descuento' => round(($formulario->totales['total_retenciones'] + $formulario->totales['total_descuentos']), 2) . ' ___ ' . round($formulario->total_retencion_descuento, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_total_retencion_descuento' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function totalBonificacion()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'total_bonificacion' => $formulario->totales['total_bonificaciones']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaTotalBonificacion()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->totales['total_bonificaciones'], 2) != round($formulario->total_bonificacion, 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_total_bonificacion' => round($formulario->totales['total_bonificaciones'], 2) . ' ___ ' . round($formulario->total_bonificacion, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_total_bonificacion' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function regaliaMinera()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'regalia_minera' => $formulario->totales['total_minerales']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaRegaliaMinera()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->totales['total_minerales'], 2) != round($formulario->regalia_minera, 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_regalia_minera' => round($formulario->totales['total_minerales'], 2) . ' ___ ' . round($formulario->regalia_minera, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_regalia_minera' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function formularioDescuento()
    {
        $formulariosDescuentos = FormularioDescuento::where('id', '>', '800')->get();
        try {
            foreach ($formulariosDescuentos as $formulario) {
                \DB::table('formulario_descuento')
                    ->where('formulario_liquidacion_id', $formulario->formulario_liquidacion_id)
                    ->where('descuento_bonificacion_id', $formulario->descuento_bonificacion_id)
                    ->update(['valor' => $formulario->descuentoBonificacion->valor,
                        'unidad' => $formulario->descuentoBonificacion->unidad,
                        'en_funcion' => $formulario->descuentoBonificacion->en_funcion]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function saldoFavor()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'saldo_favor' => $formulario->totales['total_saldo_favor']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaSaldoFavor()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->totales['total_saldo_favor'], 2) != round(($formulario->saldo_favor), 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_saldo_favor' => $formulario->totales['total_saldo_favor'] . ' ___ ' . $formulario->saldo_favor]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_saldo_favor' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaNetoVenta()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->valor_neto_venta, 2) != round(($formulario->neto_venta), 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_neto_venta' => round($formulario->valor_neto_venta, 2) . ' ___ ' . round(($formulario->neto_venta), 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_neto_venta' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaPesoSeco()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->peso_neto_seco, 2) != round($formulario->peso_seco, 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_peso_seco' => round($formulario->peso_neto_seco, 2) . ' ___ ' . round($formulario->peso_seco, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_peso_seco' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function storeDocumentosComprasUno()
    {
        $formularios = FormularioLiquidacion::where('id', '<=', '200')->orderBy('id')->get();
        try {
            foreach ($formularios as $formulario) {
                for ($i = 0; $i < count(Fachada::getTiposDocumentosCompras()); $i++) {
                    $valor['descripcion'] = Fachada::getTiposDocumentosCompras()[$i];
                    $valor['formulario_liquidacion_id'] = $formulario->id;
                    if ($formulario->url_documento)
                        $valor['agregado'] = true;
                    DocumentoCompra::create($valor);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function humedad()
    {
        $formularios = FormularioLiquidacion::where('id', '<=', '600')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'humedad_promedio' => $formulario->humedad]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function humedadDos()
    {
        $formularios = FormularioLiquidacion::where('id', '>', '800')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'humedad_promedio' => $formulario->humedad]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaHumedad()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->humedad, 2) != round($formulario->humedad_promedio, 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_humedad' => round($formulario->humedad, 2) . ' ___ ' . round($formulario->humedad_promedio, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_humedad' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function humedadKg()
    {
        $formularios = FormularioLiquidacion::where('id', '>', '1500')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'humedad_kilo' => $formulario->humedad_kg]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function humedadKgDos()
    {
        $formularios = FormularioLiquidacion::where('id', '>', '800')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'humedad_kilo' => $formulario->humedad_kg]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function pruebaHumedadKg()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->humedad_kg, 5) != round($formulario->humedad_kilo, 5)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_humedad_kg' => round($formulario->humedad_kg, 5) . ' ___ ' . round($formulario->humedad_kilo, 5)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_humedad_kg' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function totalCuentaCobrar()
    {
        $formularios = FormularioLiquidacion::where('id', '<=', '600')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'total_cuenta_cobrar' => $formulario->totales['total_cuentas_cobrar']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function totalCuentaCobrarDos()
    {
        $formularios = FormularioLiquidacion::where('id', '>', '600')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'total_cuenta_cobrar' => $formulario->totales['total_cuentas_cobrar']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaCuentaCobrar()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->totales['total_cuentas_cobrar'], 2) != round($formulario->total_cuenta_cobrar, 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_cuenta_cobrar' => round($formulario->totales['total_cuentas_cobrar'], 2) . ' ___ ' . round($formulario->total_cuenta_cobrar, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_cuenta_cobrar' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function leySn()
    {
        $formularios = FormularioLiquidacion::where('letra', 'D')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'ley_sn' => $formulario->ley_estanio]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function leyAg()
    {
        $formularios = FormularioLiquidacion::where('letra', 'E')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'ley_ag_e' => $formulario->ley_ag]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pruebaLeySn()
    {
        $formularios = FormularioLiquidacion::where('fecha_liquidacion', '>', '2023-07-29')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                if (round($formulario->ley_estanio, 2) != round($formulario->ley_sn, 2)) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_ley_sn' => round($formulario->ley_estanio, 2) . ' ___ ' . round($formulario->ley_sn, 2)]);
                } else {
                    \DB::table('formulario_liquidacion')->
                    where('id', $formulario->id)->update([
                        'prueba_ley_sn' => '========']);
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function totalDevolucion()
    {
        $formularios = FormularioLiquidacion::where('id', '<=', '600')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'total_cuenta_cobrar' => $formulario->totales['total_cuentas_cobrar']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function totalDevolucionDos()
    {
        $formularios = FormularioLiquidacion::where('id', '>', '800')->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'total_cuenta_cobrar' => $formulario->totales['total_cuentas_cobrar']]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function mermaSacos()
    {
        $formularios = FormularioLiquidacion::whereLetra('E')->where('id', '>', '2935')->whereEstado(Estado::EnProceso)->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'presentacion' => 'Ensacado',
                    'sacos' => (round($formulario->peso_bruto / 40)), 'merma' => 0]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function taraPlata()
    {
        $formularios = FormularioLiquidacion::whereLetra('E')->where('id', '>', '2935')->whereEstado(Estado::EnProceso)->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'tara' => ($formulario->sacos * 0.250)]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }
//    public function pruebaHumedad()
//    {
//        $formularios = FormularioLiquidacion::where('id', '>', '1800')->where('id), '<-, '2400>whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
//        try {
//            foreach ($formularios as $formulario) {
//                if (round($formulario->totales['total_anticipos'], 2) != round($formulario->total_anticipo, 2))
//                {
//                    \DB::table('formulario_liquidacion')->
//                    where('id', $formulario->id)->update([
//                        'prueba_total_anticipo' =>round($formulario->totales['total_anticipos'], 2).' ___ '. round($formulario->total_anticipo, 2)]);
//                }
//                else{
//                    \DB::table('formulario_liquidacion')->
//                    where('id', $formulario->id)->update([
//                        'prueba_total_anticipo' =>'========']);
//                }
//            }
//            return response()->json(['res' => true], 200);
//        } catch (\Exception $e) {
//            return $this->make_exception($e);
//        }
//    }


    public function actualizarCuentaCobrar()
    {

        $cuentas = CuentaCobrar::get();
        try {
            foreach ($cuentas as $cuenta) {
                $motivo = explode(' ', $cuenta->motivo);
                $ultimaPalabra = array_pop($motivo);
                $saldo = "SALDO";

                $contador = CuentaCobrar::whereId($cuenta->id)->where('motivo', 'ilike', "%{$saldo}%")->count();
                if ($contador > 0) {
                    $numero = substr($ultimaPalabra, 0, -2);
                    $numero = (int)filter_var($numero, FILTER_SANITIZE_NUMBER_INT);
                    $anio = '20' . (substr($ultimaPalabra, -2));
                    $letra = substr($ultimaPalabra, -4, 1);
                    $formulario =
                        FormularioLiquidacion::where('anio', $anio)->where('numero_lote', $numero)->where('letra', $letra)->first();
                    $id = $formulario->id;
                    \DB::table('cuenta_cobrar')->whereId($cuenta->id)->update(['formulario_liquidacion_id' => $id]);
                } else {
                    $pago = PagoMovimiento::whereAnio($cuenta->anio)->whereCodigo($ultimaPalabra)->first();
                    $id = $pago->origen_id;
                    \DB::table('cuenta_cobrar')->whereId($cuenta->id)->update(['prestamo_id' => $id]);

                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }

    }

    public function actualizarPassCliente()
    {
        $clientes = Cliente::get();
        try {
            foreach ($clientes as $cliente) {
                \DB::table('cliente')->
                where('id', $cliente->id)->update([
                    'password' => $cliente->nit]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function puntos()
    {
        $formularios = FormularioLiquidacion::where('letra', 'D')->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'puntos' => $formulario->puntos_calculo]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function taraAnimas()
    {
        $formularios = FormularioLiquidacion::
        where(function ($q) {
            $q->where('letra', "E")
                ->whereEstado(Estado::EnProceso)
                ->WhereHas('cliente', function ($q) {
                    $q->where('cooperativa_id', "44");
                });
        })
            ->orderByDesc('id')->orderByDesc('numero_lote')
            ->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'tara' => (0.250 * $formulario->sacos)]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function cotizacionPromedioAgAnimas()
    {
        $formularios = FormularioLiquidacion::
        where(function ($q) {
            $q->where('letra', "E")
                ->WhereHas('cliente', function ($q) {
                    $q->where('cooperativa_id', "44");
                });
        })
            ->orderByDesc('id')->orderByDesc('numero_lote')
            ->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'con_cotizacion_promedio' => true]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function obsCotizacionPromedioAgNoAnimas()
    {
        $formularios = FormularioLiquidacion::
        where(function ($q) {
            $q->where('fecha_hora_liquidacion', '>', "2023-01-25 00:00:00")
                ->where('letra', "E")
                ->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])
                ->WhereHas('cliente', function ($q) {
                    $q->where('cooperativa_id', '<>', "44");
                });
        })
            ->orderByDesc('id')->orderByDesc('numero_lote')
            ->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'observacion' => 'Se liquidó con cotización diaria promedio']);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function proveedoresAMovimiento()
    {
        $pagos = PagoMovimiento::
        whereOrigenType(Movimiento::class)
            ->orderBy('id')
            ->get();
        try {
            foreach ($pagos as $pago) {
                \DB::table('movimiento')->
                where('id', $pago->origen_id)->update([
                    'proveedor_id' => $pago->proveedor_id]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function netoVentaPesoSecoVenta()
    {
        $ventas = Venta::
        orderBy('id')
            ->get();
        try {
            foreach ($ventas as $venta) {
                \DB::table('venta')->
                where('id', $venta->id)->update([
                    'peso_neto_seco' => $venta->suma_peso_neto_seco,
                    'valor_neto_venta' => $venta->suma_valor_neto_venta]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function montoFinalVenta()
    {
        $ventas = Venta::where('estado', EstadoVenta::Liquidado)
            ->where('es_cancelado', false)
            ->where('es_aprobado', true)
            ->where('monto', '0.00')
            ->orderByDesc('numero_lote')->orderByDesc('id')
            ->get();
        try {
            foreach ($ventas as $venta) {
                \DB::table('venta')->
                where('id', $venta->id)->update([
                    'es_cancelado' => true, 'fecha_cobro' => date('Y-m-d')]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function netoHumedoConcentrado()
    {
        $concentrados = Concentrado::orderBy('id')
            ->get();
        try {
            foreach ($concentrados as $concentrado) {
                \DB::table('concentrado')->
                where('id', $concentrado->id)->update([
                    'peso_neto_humedo' => $concentrado->neto_humedo]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function pesosEnsayos()
    {
        $labs = LaboratorioEnsayo::
        orderBy('id')
            ->get();
        try {
            foreach ($labs as $lab) {
                \DB::table('laboratorio_peso_humedad')
                    ->insert([
                        'peso_humedo' => $lab->peso_humedo,
                        'peso_seco' => $lab->peso_seco,
                        'peso_tara' => $lab->peso_tara,
                        'laboratorio_ensayo_id' => $lab->id
                    ]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function numerosEnPagos()
    {
        $pagos = PagoMovimiento::
        orderBy('created_at')
            ->orderBy('id')
            ->get();
        $i = 1;
        try {
            foreach ($pagos as $pago) {
                \DB::table('pago_movimiento')->
                where('id', $pago->id)->update([
                    'numero' => ($i)]);
                $i = $i + 1;

            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function despachadoVenta()
    {
        $ventas = Venta::
        orderBy('id')
            ->get();
        try {
            foreach ($ventas as $venta) {
                \DB::table('venta')->
                where('id', $venta->id)->update([
                    'es_despachado' => ($venta->esta_despachado)]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function ventaMineral()
    {
        try {
            $ventas = Venta::
            orderBy('id')
                ->get();
            foreach ($ventas as $venta) {
                $producto = Producto::whereLetra($venta->letra)->first();
                $productoMinerales = $producto->productoMinerals;
                foreach ($productoMinerales as $row) {
                    if (!$row->es_penalizacion) {
                        $campos['venta_id'] = $venta->id;
                        $campos['mineral_id'] = $row->mineral_id;
                        switch ($row->mineral_id) {
                            case 1:
                                $campos['peso_fino'] = $venta->suma_peso_fino_ag;
                                break;
                            case 2:
                                $campos['peso_fino'] = $venta->suma_peso_fino_pb;
                                break;
                            case 3:
                                $campos['peso_fino'] = $venta->suma_peso_fino_zn;
                                break;
                            case 4:
                                $campos['peso_fino'] = $venta->suma_peso_fino_sn;
                                break;
                            case 5:
                                $campos['peso_fino'] = $venta->suma_peso_fino_cu;
                                break;
                            case 6:
                                $campos['peso_fino'] = $venta->suma_peso_fino_sb;
                                break;
                            case 7:
                                $campos['peso_fino'] = $venta->suma_peso_fino_au;
                                break;
                        }
                        //$campos['peso_fino'] = $row->mineral_id;
                        VentaMineral::create($campos);
                    }
                }
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }

    }


    public function fechaPromedioVenta($id)
    {
        try {
            $ventasFormularios = VentaFormularioLiquidacion::whereVentaId($id)->get();

            $sumatoriaSeco = 0;
            foreach ($ventasFormularios as $ventaFormulario) {
                $sumatoriaSeco = $sumatoriaSeco + $ventaFormulario->formulario->peso_seco;
                FormularioLiquidacion::where('id', $ventaFormulario->formulario_liquidacion_id)->update(['estado' => Estado::Vendido]);
                VentaFormularioLiquidacion::where('id', $ventaFormulario->id)->update(['peso_acumulado' => $ventaFormulario->calculo_peso]);
            }

            $concentrados = Concentrado::whereTipoLote(TipoLoteVenta::Ingenio)->whereVentaId($id)->get();

            foreach ($concentrados as $concentrado) {
                $sumatoriaSeco = $sumatoriaSeco + $ventaFormulario->peso_neto_seco;
                Concentrado::where('id', $concentrado->id)->update(['peso_acumulado' => $concentrado->calculo_peso]);
            }
            $sumatoriaSeco = $sumatoriaSeco / 2;

            $formElegido = FormularioLiquidacion::
            join('venta_formulario_liquidacion', 'formulario_liquidacion.id', '=', 'venta_formulario_liquidacion.formulario_liquidacion_id')
                ->where('venta_formulario_liquidacion.venta_id', $id)
                ->where('peso_acumulado', '>=', $sumatoriaSeco)
                ->orderBy('fecha_liquidacion')
                ->first();

            $ingenioElegido = Concentrado::whereVentaId($id)
                ->where('peso_acumulado', '>=', $sumatoriaSeco)
                ->whereTipoLote(TipoLoteVenta::Ingenio)
                ->orderBy('fecha')
                ->first();

            $fechaElegida = $formElegido->fecha_liquidacion;
            if ($ingenioElegido) {
                if ($ingenioElegido->peso_acumulado < $formElegido->peso_acumulado)
                    $fechaElegida = $ingenioElegido->fecha;
            }

            \DB::table('venta')->where('id', $id)->update(['fecha_promedio' => $fechaElegida]);
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function esRetirado()
    {
        $forms = FormularioLiquidacion::
        orderBy('id')
            ->get();
        try {
            foreach ($forms as $form) {
                $dev = Bono::whereFormularioLiquidacionId($form->id)->first();
                if ($dev and $form->estado == Estado::Anulado) {
                    \DB::table('formulario_liquidacion')->
                    where('id', $form->id)->update([
                        'es_retirado' => true]);
                }

            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function retirarLote()
    {
        try {
            $devoluciones = PagoMovimiento::
            join('bono', 'bono.id', '=', 'pago_movimiento.origen_id')
                ->join('formulario_liquidacion', 'formulario_liquidacion.id', '=', 'bono.formulario_liquidacion_id')
                ->where('bono.es_cancelado', true)
                ->where('origen_type', Bono::class)
                ->orderByDesc('formulario_liquidacion.id')
                ->get();

            foreach ($devoluciones as $dev) {
                \DB::table('formulario_liquidacion')->
                where('id', $dev->formulario_liquidacion_id)->update([
                    'es_retirado' => true]);

            }

            $devoluciones = Bono::
            join('formulario_liquidacion', 'formulario_liquidacion.id', '=', 'bono.formulario_liquidacion_id')
                ->where('bono.clase', ClaseDevolucion::Externo)
                ->orderByDesc('formulario_liquidacion.id')
                ->get();

            foreach ($devoluciones as $dev) {
                \DB::table('formulario_liquidacion')->
                where('id', $dev->formulario_liquidacion_id)->update([
                    'es_retirado' => true]);

            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function detalleActivo()
    {
        $activos = ActivoFijo::
        orderBy('id')
            ->get();
        try {
            foreach ($activos as $activo) {

                \DB::table('activo.detalle_activo')
                    ->insert([
                        'cantidad' => $activo->cantidad, 'descripcion' => $activo->descripcion, 'valor_unitario' => $activo->valor_unitario,
                        'activo_fijo_id' => $activo->id
                    ]);

            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function origenEnsayo()
    {
        $ensayos = Ensayo::whereNotNull('formulario_liquidacion_id')
        ->orderBy('id')
            ->get();
        try {
            foreach ($ensayos as $ensayo) {

                \DB::table('laboratorio.ensayo')->where('id', $ensayo->id)
                    ->update([
                        'origen_id' => $ensayo->formulario_liquidacion_id,
                        'origen_type' => FormularioLiquidacion::class
                    ]);

            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }



    public function montoLaboratorios()
    {
        $pedidos = Recepcion::where('created_at','<', '2023-09-21 00:00:00')
            ->orderBy('id')
            ->get();
        $glosa= 'PAGO FINAL DE ANÁLISIS DE LABORATORIO DE SN';
        try {
            foreach ($pedidos as $pedido) {

                \DB::table('laboratorio.pago_movimiento')->where('origen_id', $pedido->id)
                    ->where('origen_type', Recepcion::class)
                    ->where('glosa', 'ilike', $glosa.'%')
                    ->update([
                        'monto' => ($pedido->monto_estanio + $pedido->monto_humedad)
                    ]);

            }

            $ensayos = Ensayo::where('created_at','<', '2023-09-21 00:00:00')
                ->whereElementoId(1)
                ->orderBy('id')
                ->get();


            foreach ($ensayos as $ensayo) {

                \DB::table('laboratorio.ensayo')->where('id', $ensayo->id)
                    ->update([
                        'precio_unitario' => 40
                    ]);

            }

            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function descuentosAcumulativos()
    {
        $cooperativas = Cooperativa::
            orderBy('id')
            ->get();
        $contrato=Contrato::first();
        try {
            foreach ($cooperativas as $cooperativa) {

                \DB::table('descuento_bonificacion')
                    ->insert([
                        'nombre' => 'BONO EQUIPAMIENTO',
                        'valor' => $contrato->bono_equipamiento,
                        'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada,
                        'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco,
                        'tipo' => 'Bonificacion',
                        'cooperativa_id' => $cooperativa->id,
                        'clase' => ClaseDescuento::Acumulativo
                    ]);

                \DB::table('descuento_bonificacion')
                    ->insert([
                        'nombre' => 'BONO PRODUCTOR',
                        'valor' => $contrato->bono_productor,
                        'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada,
                        'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco,
                        'tipo' => 'Bonificacion',
                        'cooperativa_id' => $cooperativa->id,
                        'clase' => ClaseDescuento::Acumulativo
                    ]);

                \DB::table('descuento_bonificacion')
                    ->insert([
                        'nombre' => 'BONO EPP',
                        'valor' => $contrato->bono_epp,
                        'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada,
                        'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco,
                        'tipo' => 'Bonificacion',
                        'cooperativa_id' => $cooperativa->id,
                        'clase' => ClaseDescuento::Acumulativo
                    ]);

                \DB::table('descuento_bonificacion')
                    ->insert([
                        'nombre' => 'BONO CLIENTE',
                        'valor' => $contrato->bono_cliente,
                        'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada,
                        'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco,
                        'tipo' => 'Bonificacion',
                        'cooperativa_id' => $cooperativa->id,
                        'clase' => ClaseDescuento::EnLiquidacion,
                        'agregado_por_defecto' => false
                    ]);

                \DB::table('descuento_bonificacion')
                    ->insert([
                        'nombre' => 'BONO CALIDAD',
                        'valor' => 30,
                        'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada,
                        'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco,
                        'tipo' => 'Bonificacion',
                        'cooperativa_id' => $cooperativa->id,
                        'clase' => ClaseDescuento::EnLiquidacion,
                        'agregado_por_defecto' => false
                    ]);

            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function clientesAnticipo()
    {
        $anticipos = Anticipo::
            orderBy('id')
            ->get();
        try {
            foreach ($anticipos as $anticipo) {

                \DB::table('anticipo')->where('id', $anticipo->id)
                    ->update([
                        'cliente_pago' => $anticipo->formularioLiquidacion->cliente_id,
                    ]);

            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function descuentosAcumulativosCambios()
    {
        $descuentos = DescuentoBonificacion::
            whereClase(ClaseDescuento::Acumulativo)
        ->orderBy('id')
            ->get();
        try {
            foreach ($descuentos as $descuento) {
                \DB::table('formulario_descuento')->where('descuento_bonificacion_id', $descuento->id)
                    ->update([
                        'en_funcion' => EnFuncion::PesoNetoSeco,
                        'unidad' => UnidadDescuentoBonificacion::DolarPorTonelada
                    ]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function descuentosLibertad()
    {
        $formularios = FormularioLiquidacion::
            where('id', '<>', 8249)
            ->where('id', '<>', 8253)
            ->where('id', '<>', 8255)
            ->whereHas('cliente', function ($q) {
                $q->where('cooperativa_id', 75)
                    ;
            })
            ->orderBy('id')
            ->get();
        try {
            foreach ($formularios as $formulario) {

                \DB::table('formulario_descuento')
                    ->insert([
                        'formulario_liquidacion_id' => $formulario->id,
                        'descuento_bonificacion_id' => 627,
                        'en_funcion' => 'Valor Neto de Venta',
                        'unidad' => 'Porcentaje',
                        'valor' => 0.40,
                    ]);

                \DB::table('formulario_descuento')
                    ->insert([
                        'formulario_liquidacion_id' => $formulario->id,
                        'descuento_bonificacion_id' => 628,
                        'en_funcion' => 'Valor Neto de Venta',
                        'unidad' => 'Porcentaje',
                        'valor' => 0.50,
                    ]);

                \DB::table('formulario_descuento')
                    ->insert([
                        'formulario_liquidacion_id' => $formulario->id,
                        'descuento_bonificacion_id' => 629,
                        'en_funcion' => 'Valor Neto de Venta',
                        'unidad' => 'Porcentaje',
                        'valor' => 1.00,
                    ]);

                \DB::table('formulario_descuento')
                    ->insert([
                        'formulario_liquidacion_id' => $formulario->id,
                        'descuento_bonificacion_id' => 630,
                        'en_funcion' => 'Valor Neto de Venta',
                        'unidad' => 'Porcentaje',
                        'valor' => 5.00,
                    ]);



            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }


    public function antiguaTaraAnimas()
    {
        $formularios = FormularioLiquidacion::
        where('letra', "E")
            ->where('anio', '2024')
            ->where('numero_lote', '>=', 301)
            ->where('numero_lote', '<=', 330)
            ->orderByDesc('id')->orderByDesc('numero_lote')
            ->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'tara' => $formulario->sacos * 0.225]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function cotizacionManualAnimas()
    {
        $formularios = FormularioLiquidacion::
        where('letra', "E")
            ->where('anio', '2024')
            ->where('numero_lote', '>=', 301)
            ->where('numero_lote', '<=', 330)
            ->orderByDesc('id')->orderByDesc('numero_lote')
            ->get();
        try {
            foreach ($formularios as $formulario) {
                \DB::table('formulario_liquidacion')->
                where('id', $formulario->id)->update([
                    'con_cotizacion_promedio' => true,
                    'es_cotizacion_manual' => true, 'cotizacion_manual' => 24.878]);
            }
            return response()->json(['res' => true], 200);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }
}
