<?php

namespace App\Models;

use App\Patrones\Fachada;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CotizacionOficial extends Model
{

    public $table = 'cotizacion_oficial';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'fecha',
        'monto',
        'unidad',
        'alicuota_exportacion',
        'alicuota_interna',
        'es_aprobado',
        'mineral_id'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha' => 'required',
        'monto' => 'required|numeric',
        'unidad' => 'required|string|max:10',
        'mineral_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public $appends = ['fechaFinal'];


    public function getFechaFinalAttribute()
    {
        $fecha = new \DateTime($this->fecha);

        if($fecha->format('d')>15)
        {
            return (date("t/m/Y", strtotime($this->fecha)));
        }
        else{
            return (date("15/m/Y", strtotime($this->fecha)));
        }
//        return $fecha->add(new \DateInterval('P15D'))->format('d/m/Y');
    }

    public function getFechaInicioAttribute()
    {
        $fecha = new \DateTime($this->fecha);

        if($fecha->format('d')>15)
            return (date("16/m/Y", strtotime($this->fecha)));
        else
            return (date("01/m/Y", strtotime($this->fecha)));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mineral()
    {
        return $this->belongsTo(\App\Models\Material::class, 'mineral_id');
    }
}
