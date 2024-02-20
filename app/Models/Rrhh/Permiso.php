<?php

namespace App\Models\Rrhh;
use App\Models\Personal;

use Eloquent as Model;

class Permiso extends Model
{
    public $table = 'rrhh.permiso';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'inicio',
        'fin',
        'motivo',
        'tipo',
        'es_aprobado',
        'personal_id',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    
}
