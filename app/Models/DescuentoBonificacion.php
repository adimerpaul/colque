<?php

namespace App\Models;

use App\Patrones\TipoDescuentoBonificacion;
use Eloquent as Model;


class DescuentoBonificacion extends Model
{
    public $table = 'descuento_bonificacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'nombre',
        'valor',
        'unidad',
        'en_funcion',
        'tipo',
        'alta',
        'clase',
        'cooperativa_id',
        'agregado_por_defecto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'valor' => 'decimal:2',
        'unidad' => 'string',
        'tipo' => 'string',
        'en_funcion' => 'string',
        'cooperativa_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'required|max:100',
        'valor' => 'required',
        'unidad' => 'required|string',
        'tipo' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public $hidden = ['created_at', 'updated_at'];

    public function getUnidadSimboloAttribute()
    {
        $unidad = 'USD/TON';
        if ($this->unidad === 'Porcentaje')
            $unidad = '%';
        elseif ($this->unidad === 'Constante')
            $unidad = 'CTE';

        return $unidad;
    }

    public function getTipoDenominacionAttribute()
    {
        $tipo = 'RETENCIÓN DE LEY';
        if ($this->tipo === TipoDescuentoBonificacion::Bonificacion)
            $tipo = 'BONIFICACIÓN';
        elseif ($this->tipo === TipoDescuentoBonificacion::Descuento)
            $tipo = 'DEDUCCIÓN INSTITUCIONAL';

        return $tipo;
    }

    public function getYaSeUtilizoAttribute(){
        $utilizado = false;
        $contador= FormularioDescuento::whereDescuentoBonificacionId($this->id)->count();
        if($contador>0)
            $utilizado = true;

        return $utilizado;
    }

    public function formularioLiquidaciones()
    {
        return $this->belongsToMany(FormularioLiquidacion::class, 'formulario_descuento', 'descuento_bonificacion_id', 'formulario_liquidacion_id');
    }

    public function cooperativa()
    {
        return $this->belongsTo(Cooperativa::class);
    }
}
