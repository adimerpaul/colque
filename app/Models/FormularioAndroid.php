<?php

namespace App\Models;

use App\Http\Controllers\ApiDescuentoBonificacionController;
use App\Http\Controllers\BonoController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CostoController;
use App\Http\Controllers\CotizacionDiariaController;
use App\Http\Controllers\CuentaCobrarController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\LiquidacionMineralController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoMineralController;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\Estado;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioAndroid extends Model
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
        'peso_bruto',
        'sacos',
        'producto',


    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at', 'updated_at',
        'fecha_cotizacion',
        'fecha_liquidacion',
        'fecha_pesaje',
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
        'observacion',
        'numero_tornaguia',
        'url_documento',
        'aporte_fundacion',
        'saldo_favor',
        'tipo_cambio_id',
        'cliente_id',
        'chofer_id',
        'vehiculo_id',
        'neto_venta',
        'es_cancelado',
        'fecha_cancelacion',
        'tara',
        'merma',
        'valor_por_tonelada',
        'peso_seco',
        'estado',
        "prueba_liquido_pagable",
        "prueba_total_anticipo",
        "prueba_total_bonificacion",
        "prueba_total_retencion_descuento",
        "prueba_regalia_minera",
        "prueba_valor_neto_pagable",
        "prueba_cuenta_cobrar",
        "prueba_humedad",
        "prueba_humedad_kg",
        "prueba_ley_sn",
        "humedad_kilo",
        "laboratorio",
        "numero_analisis",
        "fecha_analisis",
        "regalia_minera",
        'contrato_plantilla_id',

    ];


    public $appends = [
        'lote',
        'ley_producto',
        'laboratorio_promedio'
    ];


    public function getLoteAttribute()
    {
        return sprintf("%s%d%s/%s", $this->sigla, $this->numero_lote, $this->letra, substr($this->anio, 2, 2));
    }


    public function getLaboratorioPromedioAttribute()
    {
        $obj = new LaboratorioController();
        if($this->created_at >'2024-01-14 00:00:00')
            return $obj->getPromedios2($this->id);
        else
            return $obj->getPromedios($this->id);
    }

    public function getLeyProductoAttribute()
    {
        $ley = '';
        foreach ($this->laboratorio_promedio as $lab) {
            if ($lab->simbolo == 'Zn') {
                $ley = $ley . ', Zinc: ' . number_format($lab->promedio, 2);
            } elseif ($lab->simbolo == 'Pb') {
                $ley = $ley . ', Plomo: ' . number_format($lab->promedio, 2);
            } elseif ($lab->simbolo == 'Ag') {
                $ley = $ley . ', Plata: ' . number_format($lab->promedio, 2);
            } elseif ($lab->simbolo == 'Sn') {
                $ley = $ley . ', Estaño: ' . number_format($lab->promedio, 2);
            } elseif ($lab->simbolo == 'Sb') {
                $ley = $ley . ', Antimonio: ' . number_format($lab->promedio, 2);
            } elseif ($lab->simbolo == 'Au') {
                $ley = $ley . ', Oro: ' . number_format($lab->promedio, 2);
            }
        }
        return substr($ley, 2);
    }

}
