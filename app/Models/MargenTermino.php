<?php

namespace App\Models;

use Eloquent as Model;

class MargenTermino extends Model
{

    public $table = 'margen_termino';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'ley',
        'margen_zn',
        'margen_pb'
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

}
