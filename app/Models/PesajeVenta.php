<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesajeVenta extends Model
{
    use HasFactory;

    public $table = 'pesaje_venta';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'numero_pesaje',
        'tara',
        'peso_bruto_humedo',
        'venta_id',
        'chofer_id',
        'vehiculo_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'numero_pesaje' => 'required',
        'chofer_id' => 'required',
        'vehiculo_id' => 'required',
        'peso_bruto_humedo' => 'required',
        'tara' => 'required',
    ];

    public $appends = [
        'peso_neto_humedo',
        'humedad_kg',
        'peso_neto_seco',
    ];

    public function getHumedadKgAttribute()
    {
        $humedadPromedio = $this->venta->humedad_compras;
        return $this->peso_neto_humedo * ($humedadPromedio / 100);
    }

    public function getPesoNetoHumedoAttribute()
    {
        return ($this->peso_bruto_humedo - $this->tara);
    }
    public function getPesoNetoSecoAttribute()
    {
        return ($this->peso_neto_humedo - $this->humedad_kg - $this->venta->merma_compras);
    }

    public function venta()
    {
        return $this->belongsTo(\App\Models\Venta::class, 'venta_id');
    }

    public function chofer()
    {
        return $this->belongsTo(\App\Models\Chofer::class, 'chofer_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(\App\Models\Vehiculo::class, 'vehiculo_id');
    }
}
