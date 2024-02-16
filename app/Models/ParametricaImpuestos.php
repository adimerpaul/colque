<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametricaImpuestos extends Model
{
    public $table = 'parametrica_impuestos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'codigo',
        'nombre',
        'tipo'
    ];
}
