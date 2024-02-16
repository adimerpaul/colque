<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Contrato extends Model
{
    use HasFactory;

    public $table = 'contrato';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'porcentaje_arsenico',
        'porcentaje_antimonio',
        'porcentaje_bismuto',
        'porcentaje_estanio',
        'porcentaje_hierro',
        'porcentaje_silico',
        'porcentaje_zinc',
        'deduccion_elemento',
        'deduccion_plata',
        'porcentaje_pagable_elemento',
        'porcentaje_pagable_plata',
        'maquila',
        'base',
        'escalador',
        'deduccion_refinacion_onza',
        'refinacion_libra_elemento',
        'laboratorio',
        'molienda',
        'manipuleo',
        'margen_administrativo',
        'transporte',
        'roll_back',
        'producto_id',

        'bono_cliente',
        'bono_productor',
        'bono_equipamiento',
        'bono_refrigerio',
        'bono_epp',
        'transporte_interno',
        'transporte_exportacion',
        'laboratorio_interno',
        'laboratorio_exportacion',
        'publicidad'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'porcentaje_arsenico' => 'decimal:2',
        'porcentaje_antimonio' => 'decimal:2',
        'porcentaje_bismuto' => 'decimal:2',
        'porcentaje_estanio' => 'decimal:2',
        'porcentaje_hierro' => 'decimal:2',
        'porcentaje_silico' => 'decimal:2',
        'porcentaje_zinc' => 'decimal:2',
        'deduccion_elemento' => 'decimal:3',
        'deduccion_plata' => 'decimal:3',
        'porcentaje_pagable_elemento' => 'decimal:2',
        'porcentaje_pagable_plata' => 'decimal:2',
        'maquila' => 'decimal:3',
        'base' => 'decimal:3',
        'escalador' => 'decimal:3',
        'deduccion_refinacion_onza' => 'decimal:3',
        'refinacion_libra_elemento' => 'decimal:3',
        'laboratorio' => 'decimal:3',
        'molienda' => 'decimal:3',
        'manipuleo' => 'decimal:3',
        'margen_administrativo' => 'decimal:3',
        'transporte' => 'decimal:3',
        'roll_back' => 'decimal:3',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($isNew = true)
    {
        return [
        'porcentaje_arsenico' => 'required',
        'porcentaje_antimonio' => 'required',
        'porcentaje_bismuto' => 'required',
        'porcentaje_estanio' => 'required',
        'porcentaje_hierro' => 'required',
        'porcentaje_silico' => 'required',
        'porcentaje_zinc' => 'required',
        'deduccion_elemento' => 'required',
        'deduccion_plata' => 'required',
        'porcentaje_pagable_elemento' => 'required',
        'porcentaje_pagable_plata' => 'required',
      //  'maquila' => 'required',
     //   'base' => 'required',
        'escalador' => 'required',
        'deduccion_refinacion_onza' => 'required',
        'refinacion_libra_elemento' => 'required',
        'molienda' => 'required',
     //   'roll_back' => 'required',
        'producto_id' => 'required'.($isNew ? '|unique:contrato' : ''),
        ];
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
