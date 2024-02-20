<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cufd extends Model
{
    protected $table = 'impuestos.cufds';

    protected $fillable = [
        'transaccion',
        'codigo_punto_venta',
        'codigo',
        'codigo_control',
        'direccion',
        'fecha_vigencia',
    ];

    public function envioFacturas()
    {
        return $this->hasMany(EnvioFactura::class);
    }
}
