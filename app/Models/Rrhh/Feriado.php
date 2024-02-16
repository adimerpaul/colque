<?php

namespace App\Models\Rrhh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    use HasFactory;
    
    
    public $table = 'rrhh.feriado';
    
    protected $fillable = [
        'fecha',
        'motivo',
        'es_turno',
    ];

    
}
