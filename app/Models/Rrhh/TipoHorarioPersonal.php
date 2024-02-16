<?php

namespace App\Models\Rrhh;

use App\Models\Personal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHorarioPersonal extends Model
{
    use HasFactory;
    public $table = 'rrhh.tipo_horario_personal';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'fecha_inicial',
        'fecha_fin',
        'tipo_horario_id',
        'personal_id',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class,'personal_id');
    }
    public function tiposHorarios()
    {
        return $this->belongsTo(TipoHorario::class,'tipo_horario_id');

    }

}
