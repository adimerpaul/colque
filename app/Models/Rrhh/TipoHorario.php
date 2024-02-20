<?php

namespace App\Models\Rrhh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TipoHorario extends Model
{   
    use HasFactory;
    public $table = 'rrhh.tipo_horario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'descripcion',
        'inicio_semana',
        'fin_semana',
        'inicio_sabado',
        'fin_sabado'
    ];

    public function getHorarioAttribute()
    {
        if ($this->inicio_semana && $this->inicio_sabado) {
            return "$this->descripcion $this->inicio_semana - $this->fin_semana y $this->inicio_sabado - $this->fin_sabado";
        } elseif ($this->inicio_semana) {
            return "$this->descripcion $this->inicio_semana - $this->fin_semana";
        } elseif ($this->inicio_sabado) {
            return "$this->descripcion $this->inicio_sabado - $this->fin_sabado";
        } 
    }
    



    
}
