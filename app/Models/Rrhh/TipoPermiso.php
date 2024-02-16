<?php

namespace App\Models\Rrhh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    use HasFactory;
    public $table = 'rrhh.tipo_permiso';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'descripcion',
        'cantidad_dia',
        'cantidad_hora',
        'fecha_inicio',
        'fecha_final',
    ];
}
