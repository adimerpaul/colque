<?php

namespace App\Models\Lab;

use Eloquent as Model;

class Accidente extends Model
{
    public $table = 'laboratorio.accidente';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'descripcion',
        'tipo',
        'fecha',
        'hora'
    ];
}
