<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PruebaMineral extends Model
{
    use SoftDeletes;
    public $table = 'prueba_mineral';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'simbolo',
        'nombre',
        'unidad_laboratorio',
        'ip_registro'
    ];


    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'ip_registro'];


}
