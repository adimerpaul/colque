<?php

namespace App\Models\Activo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivoFijoBaja extends Model
{
    use HasFactory;
    public $table = 'activo.baja';
    protected $fillable = [

    'detalle_activo_id',
    'cantidad',
    'motivo'

    ];

    public function detalle()
    {
        return $this->belongsTo(\App\Models\Activo\DetalleActivo::class, 'detalle_activo_id');
    }

    


}

