<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionFormulario extends Model
{
    use HasFactory;

    public $table = 'ubicacion_formulario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'formulario_liquidacion_id',
        'ubicacion_id',
        'alta',
    ];

    protected $hidden = ['created_at', 'updated_at'];

//    public $appends = [
//        'cuadros'
//    ];

    public function getCuadrosAttribute()
    {
        $ubicacion='';
           $cuadros = UbicacionFormulario::whereFormularioLiquidacionId($this->formulario_liquidacion_id)->get();
           foreach ($cuadros as $cuadro){
               $ubicacion = $ubicacion . $cuadro->ubicacion->descripcion . ', ';
           }
           return substr($ubicacion, 0, -2);

    }
    public function formulario()
    {
        return $this->belongsTo(FormularioLiquidacion::class, 'formulario_liquidacion_id', 'id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }
}
