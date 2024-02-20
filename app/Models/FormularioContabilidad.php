<?php

namespace App\Models;

use App\Http\Controllers\ApiDescuentoBonificacionController;
use App\Http\Controllers\BonoController;
use App\Http\Controllers\CuentaCobrarController;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\TipoMotivoDevolucion;
use App\Patrones\TipoPago;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormularioContabilidad extends Model
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
        'en_molienda',
        'ubicacion',
        'comision_externa',
        'con_cotizacion_promedio',
        'tipo_material',
        'es_retirado',
        'es_cotizacion_manual',
        'cotizacion_manual',
        'ip_tablet',
        'con_ley_minima',

    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at', 'updated_at'];


    public function getLoteSinGestionAttribute()
    {
        return sprintf("%s%d%s", $this->sigla, $this->numero_lote, $this->letra);
    }

    public function getCantidadDevolucionesAttribute()
    {
        $obj = new BonoController();
        return $obj->getCantidadDevoluciones($this->id);
    }

    public function getTotalCuentasCobrarAttribute()
    {
        $obj = new CuentaCobrarController();
        return $obj->getTotalFormulario($this->id, ClaseCuentaCobrar::SaldoNegativo);
    }
    public function getCuentasSaldoNegativoAttribute()
    {
        $obj = new CuentaCobrarController();
        return $obj->getTotalPorClase($this->id, ClaseCuentaCobrar::SaldoNegativo);
    }

    public function getCuentasPrestamoAttribute()
    {
        $obj = new CuentaCobrarController();
        return $obj->getTotalPorClase($this->id, ClaseCuentaCobrar::Prestamo);
    }

    public function getCuentasRetiroAttribute()
    {
        $obj = new CuentaCobrarController();
        return $obj->getTotalPorClase($this->id, ClaseCuentaCobrar::Retiro);
    }

    public function getTotalPrestamosAttribute()
    {
        $obj = new CuentaCobrarController();
        return $obj->getTotalFormulario($this->id, ClaseCuentaCobrar::Prestamo);
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

    public function getBonificacionesCooperativaAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getBonificacionesCooperativa($this->cliente->cooperativa_id, $this->id);
    }

    public function getBonificacionesDescuentosBobAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getDescuentosBonificacionesBob($this);
    }


    public function getReciboBancarioAttribute()
    {
        $pago = PagoMovimiento::whereOrigenId($this->id)->whereAlta(true)
            ->whereOrigenType(FormularioLiquidacion::class)->whereMetodo(TipoPago::CuentaBancaria)->first();
        if(!$pago){
            return '';
        }
        $glosa = strtolower($pago ->glosa);
        $frase1 = "bancaria con recibo";
        $frase = substr($glosa, strpos($glosa, $frase1) + 20);

        $frase2 = "cheque";
        if(str_contains($frase, $frase2))
            $frase = substr($frase, strpos($frase, $frase2) + 7);

        $frase3 = "no.";
        if(str_contains($frase, $frase3))
            $frase = substr($frase, strpos($frase, $frase3) + 3);
        return $frase;
    }
    public function getTipoPagoAttribute()
    {
        $pago = PagoMovimiento::whereOrigenId($this->id)->whereAlta(true)
            ->whereOrigenType(FormularioLiquidacion::class)->first();
        if(!$pago){
            return '';
        }
        $metodo= $pago->metodo;
        if($pago->metodo==TipoPago::CuentaBancaria)
            $metodo = sprintf("%s %s",$metodo, $pago->banco);

        return $metodo;
    }

    public function getDevolucionLaboratorioAttribute()
    {
        $obj = new BonoController();
        return $obj->getSuma($this->id, TipoMotivoDevolucion::Analisis);
    }

    public function getDevolucionAnticipoAttribute()
    {
        $obj = new BonoController();
        return $obj->getSuma($this->id, TipoMotivoDevolucion::Anticipo);
    }

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id');
    }

    /**
     * @return \Illuminate\Database\Elcoquent\Relations\BelongsTo
     **/

    public function descuentoBonificaciones()
    {
        return $this->belongsToMany(DescuentoBonificacion::class, 'formulario_descuento', 'formulario_liquidacion_id', 'descuento_bonificacion_id');
    }


    public function anticipos()
    {
        return $this->hasMany(Anticipo::class);
    }

    public function bonos()
    {
        return $this->hasMany(Bono::class);
    }

    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }

    public function cuenta()
    {
        return $this->morphOne(CuentaCobrar::class, 'origen');
    }
}
