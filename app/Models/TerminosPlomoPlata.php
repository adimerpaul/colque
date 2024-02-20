<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TerminosPlomoPlata extends Model
{
    use HasFactory;

    public $table = 'terminos_plomo_plata';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'ley_minima',
        'ley_maxima',
        'maquila',
        'costo_refinacion',
        'transporte',
        'rollback'
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

}
