<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvioFactura extends Model
{
    protected $table = 'envio_facturas';

    protected $fillable = [
        'codigo_punto_venta',
        'fecha_generado',
        'periodo',
        'ciclo',
        'url',
        'sha256',
        'cantidad',
        'se_envio',
        'codigo_recepcion',
        'fecha_recepcion',
        'se_valido',
        'resultado_validacion',
        'fecha_validacion',
        'cufd_id',
    ];

    public function cufd()
    {
        return $this->belongsTo(Cufd::class);
    }
}
