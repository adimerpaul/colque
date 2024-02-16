<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoCompra extends Model
{
    use HasFactory;

    public $table = 'documento_compra';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'descripcion',
        'agregado',
        'formulario_liquidacion_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];



    public function formularioLiquidacion()
    {
        return $this->belongsTo(\App\Models\FormularioLiquidacion::class, 'formulario_liquidacion_id');
    }

}
