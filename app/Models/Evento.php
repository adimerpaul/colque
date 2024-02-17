<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;
    protected $fillable = [
        'cufd',
        'fecha_inicio',
        'fecha_fin',
        'codigo_evento',
    ];
}
