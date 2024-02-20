<?php

namespace App\Models;

use Eloquent as Model;

class CotizacionDiaria extends Model
{
    public $table = 'cotizacion_diaria';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'fecha',
        'monto',
        'unidad',
        'mineral_id'
    ];


    public $appends = [
        'unidad_form',
        'monto_form',
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'monto' => 'required|numeric',
        'unidad' => 'required|string',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/

    public function getMontoFormAttribute(){
        $monto = $this->monto;
        if ($this->unidad ==\App\Patrones\UnidadCotizacion::TM) {
            $monto = ($monto / 2204.6223);
        }
        return ($monto);
    }
    public function getUnidadFormAttribute(){
        if ($this->unidad != \App\Patrones\UnidadCotizacion::OT) {
            $unidad = '$us/' . \App\Patrones\UnidadCotizacion::LF;
        }
        else {
            $unidad = '$us/' . \App\Patrones\UnidadCotizacion::OT;
        }
        return $unidad;
    }
    public function mineral()
    {
        return $this->belongsTo(\App\Models\Material::class, 'mineral_id');
    }
}
