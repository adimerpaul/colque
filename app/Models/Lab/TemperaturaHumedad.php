<?php

namespace App\Models\Lab;

use Eloquent as Model;

class TemperaturaHumedad extends Model
{
    public $table = 'laboratorio.temperatura_humedad';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'valor',
        'tipo',
        'ambiente',
        'rango_medicion_id'
    ];

    public function getFueraRangoAttribute(){
        $fuera=true;
        if($this->valor>= $this->rangoMedicion->minimo and $this->valor<= $this->rangoMedicion->maximo )
            $fuera=false;
        return $fuera;
    }

    public function rangoMedicion()
    {
        return $this->belongsTo(RangoMedicion::class);
    }
}
