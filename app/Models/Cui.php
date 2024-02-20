<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cui extends Model
{
    protected $table = 'impuestos.cuis';

    protected $fillable = [
        'transaccion',
        'codigo_punto_venta',
        'codigo',
        'fecha_vigencia',
        'numero_factura'
    ];
}
