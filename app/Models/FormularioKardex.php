<?php

namespace App\Models;

use App\Http\Controllers\ApiDescuentoBonificacionController;
use App\Http\Controllers\BonoController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CostoController;
use App\Http\Controllers\CuentaCobrarController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\LiquidacionMineralController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ValorPorToneladaController;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\Estado;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormularioKardex extends Model
{
    use HasFactory;

    public $table = 'formulario_liquidacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'sigla',
        'numero_lote',
        'letra',
        'anio',
        'fecha_cotizacion',
        'fecha_liquidacion',
        'fecha_pesaje',
        'producto',
        'peso_bruto',
        'tara',
        'merma',
        'valor_por_tonelada',
        'peso_seco',
        'estado',
        'observacion',
        'numero_tornaguia',
        'url_documento',
        'aporte_fundacion',
        'sacos',
        'saldo_favor',
        'tipo_cambio_id',
        'cliente_id',
        'chofer_id',
        'vehiculo_id',
        'neto_venta',
        'es_cancelado',
        'fecha_cancelacion',
        'tipo',
        'total_anticipo',
        'total_bonificacion',
        'total_retencion_descuento',
        'liquido_pagable',
        'prueba_saldo_favor',
        'prueba_neto_venta',
        'prueba_peso_seco',
        'motivo_anulacion',
        'presentacion',
        'fecha_hora_liquidacion',
        'boletas',
        'peso_de_balanza',
        'humedad_promedio',
        'humedad_kilo',
        'total_cuenta_cobrar',
        'ley_sn',
        'puntos',
        'comision_externa',
        'total_bonificacion_acumulativa'

    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at', 'updated_at'];




    public function getLoteSinGestionAttribute()
    {
        return sprintf("%s%d%s", $this->sigla, $this->numero_lote, $this->letra);
    }

    public function getPesoNetoAttribute()
    {
        return $this->peso_bruto - $this->tara;
    }

    public function getLaboratorioPromedioAttribute()
    {
        $obj = new LaboratorioController();
        if($this->created_at >'2024-01-14 00:00:00')
            return $obj->getPromedios2($this->id);
        else
            return $obj->getPromedios($this->id);
    }

    public function getCotizacionAgAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 1, $this->letra);
    }

    public function getCotizacionPbAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 2, $this->letra);
    }

    public function getCotizacionZnAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 3, $this->letra);
    }

    public function getCotizacionSnAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 4, $this->letra);
    }

    public function getCotizacionAuAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 7, $this->letra);
    }

    public function getCotizacionSbAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 6, $this->letra);
    }

    public function getCotizacionCuAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 5, $this->letra);
    }
    public function getCostoAttribute()
    {
        $obj = new CostoController();
        return $obj->getCosto($this->id);
    }

    public function getMermaKgAttribute()
    {
        return ($this->pesoNeto - $this->humedadKg) * ($this->merma / 100);
    }

    public function getValorNetoVentaAttribute()
    {
        if($this->cliente->cooperativa_id==44){
            if($this->created_at<'2023-02-07 00:00:00')
                return ($this->pesoNetoSeco / 1000) * $this->valor_por_tonelada * 6.96;
            else{
                $obj = new ValorPorToneladaController();
                $porcentaje = $obj->getPagablePlata($this->ley_ag, $this->fecha_liquidacion);
                if($this->ley_ag<20 and $this->fecha_liquidacion<='2023-03-20')
                    return $this->valor_por_tonelada  * 6.96;
                else
                    return $this->valor_por_tonelada * $porcentaje/100 * 6.96;
            }
        }

        else
            return ($this->pesoNetoSeco / 1000) * $this->valor_por_tonelada * $this->tipoCambio->dolar_compra;
    }


    public function getBonificacionesDescuentosBobAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getDescuentosBonificacionesBobKardex($this);
    }


    public function getCostoPesajeAttribute()
    {
        return ($this->costo->pesaje * $this->boletas);
    }

    public function getCostoPublicidadAttribute()
    {
        return (($this->peso_seco / 1000) * $this->tipoCambio->dolar_compra * $this->costo->publicidad);
    }

    public function getCostoProProductorAttribute()
    {
        return (($this->peso_seco / 1000) * $this->tipoCambio->dolar_compra * $this->costo->pro_productor);
    }



    public function getLoteVentaAttribute()
    {
        $lote='';
        $venta= VentaFormularioLiquidacion::whereFormularioLiquidacionId($this->id)->first();
        if(!empty($venta))
            $lote=$venta->venta->lote;
        return $lote;
    }

    public function getCotizacionPromedioAgAttribute(){
        if($this->letra!='E')
            return 0;

        if($this->con_cotizacion_promedio==false){
            $cotizacion = CotizacionDiaria::where('fecha', $this->fecha_cotizacion)->whereMineralId(1)->first();
            return $cotizacion->monto;
        }

        $recepcion = date( 'Y-m-d', strtotime($this->created_at));
        $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('3')->get();//->sum('monto');

        if($recepcion<'2023-02-07' and $this->cliente->cooperativa_id==44)
            return 23.62;

        if($cotizaciones->count()<3)
            return 0.00;

        $dia = date( 'D', strtotime($recepcion));
        if($dia =='Sat'){
            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 2 days'));
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('3')->get();//->sum('monto');
        }
        elseif($dia =='Fri'){
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->first();
            $cotSabado=$cotizaciones->monto;
            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 3 days'));
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('2')->get();
            $cotizaciones = ($cotizaciones->sum('monto') + $cotSabado)/3;
            return $cotizaciones;
        }

        elseif($dia =='Thu'){
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('2')->get();

            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 4 days'));
            $cotMartes = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->first();
            $cotMartes=$cotMartes->monto;
            $cotizaciones = ($cotizaciones->sum('monto') + $cotMartes)/3;
            return $cotizaciones;
        }

        return $cotizaciones->avg('monto');
    }



    public function getRetencionesCooperativaAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getRetencionesCooperativa($this->cliente->cooperativa_id, $this->id);
    }
    public function getDescuentosCooperativaAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getDescuentosCooperativa($this->cliente->cooperativa_id, $this->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id');
    }

    public function tipoCambio()
    {
        return $this->belongsTo(\App\Models\TipoCambio::class, 'tipo_cambio_id');
    }
}
