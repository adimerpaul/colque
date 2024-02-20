<?php

namespace App\Models\Activo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleActivo extends Model
{
    use HasFactory;
    public $table = 'activo.detalle_activo';
    protected $fillable = [

    'activo_fijo_id',
    'factura',
    'descripcion',
    'valor_unitario',
    'cantidad'

    ];

    public $appends = ['info'];
    public function getCantidadStockAttribute()
    {
        $cantidadBajas = ActivoFijoBaja::whereDetalleActivoId($this->id)->sum('cantidad');
        $cantidad = $this->cantidad - $cantidadBajas;
        return $cantidad;
    }

    public function getPrecioTotalAttribute()
    {
        $cantidad = $this->cantidad_stock * $this->valor_unitario;
        return $cantidad;
    }

    public function getInfoAttribute(){
        return sprintf("%s (Cantidad: %s)", $this->descripcion,  $this->cantidad_stock);
    }

    public function activoFijo()
    {
        return $this->belongsTo(ActivoFijo::class);
    }
}
