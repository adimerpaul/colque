<?php

namespace App\Models\Lab;

use Eloquent as Model;

class RangoMedicion extends Model
{
    public $table = 'laboratorio.rango_medicion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'minimo',
        'maximo',
        'tipo',
    ];

    public function getInfoAttribute(){
        return sprintf("%s - %s", $this->minimo, $this->maximo);
    }

}
