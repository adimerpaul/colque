<?php

namespace App\Models\Rrhh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Model;

class AsistenciaManual extends Model
{
    use HasFactory;
    public $table = 'rrhh.asistencia_manual';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'inicio',
        'fin',
        'motivo',
        'es_aprobado',
        'personal_id',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
