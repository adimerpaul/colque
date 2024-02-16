<?php

namespace App\Models;

use App\Patrones\TipoLoteVenta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaFormularioLiquidacion extends Model
{
    use HasFactory;

    public $table = 'venta_formulario_liquidacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'formulario_liquidacion_id',
        'venta_id',
        'despachado',
        'peso_acumulado',
        'fecha_despacho'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at'];

    public function getCalculoPesoAttribute()
    {
        $normal=FormularioLiquidacion::
        join('venta_formulario_liquidacion', 'formulario_liquidacion.id', '=', 'venta_formulario_liquidacion.formulario_liquidacion_id')
            ->where('venta_formulario_liquidacion.venta_id', $this->venta_id)
            ->where('formulario_liquidacion.fecha_liquidacion', '<=', $this->formulario->fecha_liquidacion)
            ->sum('peso_seco');

        $ingenio = Concentrado::whereVentaId($this->venta_id)
            ->where('fecha', '<=', $this->formulario->fecha_liquidacion)
            ->whereTipoLote(TipoLoteVenta::Ingenio)
            ->get()->sum('peso_neto_seco');
        return ($ingenio + $normal);
    }

    public function formulario()
    {
        return $this->belongsTo(FormularioLiquidacion::class, 'formulario_liquidacion_id', 'id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
