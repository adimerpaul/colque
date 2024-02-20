<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LaboratorioPesoHumedad extends Model
{
    use HasFactory;

    public $table = 'laboratorio_peso_humedad';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'peso_humedo',
        'peso_seco',
        'peso_tara',
        'laboratorio_ensayo_id',
    ];

    public $hidden = ['updated_at'];


    public function laboratorioEnsayo()
    {
        return $this->belongsTo(LaboratorioEnsayo::class);
    }

}
