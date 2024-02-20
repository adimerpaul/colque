<?php

namespace App\Models\Lab;

use Eloquent as Model;

class PrecioElemento extends Model
{
    public $table = 'laboratorio.precio_elemento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'monto',
        'elemento_id',
    ];
}
