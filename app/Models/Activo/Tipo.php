<?php

namespace App\Models\Activo;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    public $table = 'activo.tipo';
    protected $fillable = [
        'numero',
        'nombre',
        'prefijo',
    ];

}



