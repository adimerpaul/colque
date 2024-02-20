<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laboratorio extends Model
{
    use HasFactory;

    public $table = 'laboratorio';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'valor',
        'origen',
        'es_penalizacion',
        'formulario_liquidacion_id',
        'mineral_id',
        'unidad',
        'ml',
        'factor',
        'peso_muestra',
        'laboratorio_ensayo_id',
        'ensayo_id'
    ];

    public $hidden = ['created_at', 'updated_at'];

    public $appends = ['info_mineral', 'bloqueo_humedad', 'producto'];

    public function getInfoMineralAttribute()
    {
        if (!is_null($this->mineral_id))
            return sprintf("%s | %s", $this->mineral->simbolo, $this->mineral->nombre);
        else
            return '';
    }

    public function getBloqueoHumedadAttribute(){
        $bloqueo="";
        if(is_null($this->mineral_id) and $this->id>= 67213)
            $bloqueo="pointer-events: none;";
        return  $bloqueo;
    }

    public function getProductoAttribute(){
        $form= FormularioLiquidacion::find($this->formulario_liquidacion_id);
        $producto=$form->producto;
        return $producto;
    }

    public function formularioLiquidacion()
    {
        return $this->belongsTo(FormularioLiquidacion::class);
    }

    public function mineral()
    {
        return $this->belongsTo(Material::class, 'mineral_id');
    }
}
