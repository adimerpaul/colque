<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCatalogo extends Model
{

    public $table = 'movimiento_catalogo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'descripcion',
        'es_lote',
        'tipo'
    ];

    public function getInfoAttribute()
    {
        $desc = $this->descripcion;
        if ($this->es_lote)
            $desc = sprintf("%s%s", $desc, ' EN LOTE');
            return $desc;
    }

}
