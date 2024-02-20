<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SatisfaccionCliente extends Model
{
    use HasFactory;

    public $table = 'satisfaccion_cliente';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'descripcion',
        'ip',
        'formulario_liquidacion_id'
    ];

    public function formularioLiquidacion()
    {
        return $this->belongsTo(\App\Models\FormularioLiquidacion::class, 'formulario_liquidacion_id');
    }
}
