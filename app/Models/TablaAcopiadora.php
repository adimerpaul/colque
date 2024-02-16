<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TablaAcopiadora extends Model
{
    use HasFactory;

    public $table = 'tabla_acopiadora';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'fecha',
        'gestion',
        'nombre',
        'margen',
        'es_seleccionado',
        'cotizacion_inicial',
        'cotizacion_final',
        'l_0_incremental',
        'l_0_inicial',
        'l_5_incremental',
        'l_5_inicial',
        'l_10_incremental',
        'l_10_inicial',
        'l_15_incremental',
        'l_15_inicial',
        'l_20_incremental',
        'l_20_inicial',
        'l_25_incremental',
        'l_25_inicial',
        'l_30_incremental',
        'l_30_inicial',
        'l_35_incremental',
        'l_35_inicial',
        'l_40_incremental',
        'l_40_inicial',
        'l_45_incremental',
        'l_45_inicial',
        'l_50_incremental',
        'l_50_inicial',
        'l_55_incremental',
        'l_55_inicial',
        'l_60_incremental',
        'l_60_inicial',
        'l_65_incremental',
        'l_65_inicial',
        'l_70_incremental',
        'l_70_inicial',
        'l_75_incremental',
        'l_75_inicial',
        'l_80_incremental',
        'l_80_inicial'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'fecha' => 'datetime',
        'gestion' => 'integer',
        'nombre' => 'string',
        'margen' => 'float',
        'es_seleccionado' => 'boolean',
        'cotizacion_inicial' => 'float',
        'cotizacion_final' => 'float',
        'l_0_incremental' => 'float',
        'l_0_inicial' => 'float',
        'l_5_incremental' => 'float',
        'l_5_inicial' => 'float',
        'l_10_incremental' => 'float',
        'l_10_inicial' => 'float',
        'l_15_incremental' => 'float',
        'l_15_inicial' => 'float',
        'l_20_incremental' => 'float',
        'l_20_inicial' => 'float',
        'l_25_incremental' => 'float',
        'l_25_inicial' => 'float',
        'l_30_incremental' => 'float',
        'l_30_inicial' => 'float',
        'l_35_incremental' => 'float',
        'l_35_inicial' => 'float',
        'l_40_incremental' => 'float',
        'l_40_inicial' => 'float',
        'l_45_incremental' => 'float',
        'l_45_inicial' => 'float',
        'l_50_incremental' => 'float',
        'l_50_inicial' => 'float',
        'l_55_incremental' => 'float',
        'l_55_inicial' => 'float',
        'l_60_incremental' => 'float',
        'l_60_inicial' => 'float',
        'l_65_incremental' => 'float',
        'l_65_inicial' => 'float',
        'l_70_incremental' => 'float',
        'l_70_inicial' => 'float',
        'l_75_incremental' => 'float',
        'l_75_inicial' => 'float',
        'l_80_incremental' => 'float',
        'l_80_inicial' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'nullable|string|max:255',
        'margen' => 'numeric|min:-10|max:10',
        'cotizacion_inicial' => 'required|numeric',
        'cotizacion_final' => 'required|numeric',
        'l_0_incremental' => 'nullable|numeric',
        'l_0_inicial' => 'nullable|numeric',
        'l_5_incremental' => 'nullable|numeric|required',
        'l_5_inicial' => 'nullable|numeric|required',
        'l_10_incremental' => 'nullable|numeric|required',
        'l_10_inicial' => 'nullable|numeric|required',
        'l_15_incremental' => 'nullable|numeric|required',
        'l_15_inicial' => 'nullable|numeric|required',
        'l_20_incremental' => 'nullable|numeric|required',
        'l_20_inicial' => 'nullable|numeric|required',
        'l_25_incremental' => 'nullable|numeric|required',
        'l_25_inicial' => 'nullable|numeric|required',
        'l_30_incremental' => 'nullable|numeric|required',
        'l_30_inicial' => 'nullable|numeric|required',
        'l_35_incremental' => 'nullable|numeric|required',
        'l_35_inicial' => 'nullable|numeric|required',
        'l_40_incremental' => 'nullable|numeric|required',
        'l_40_inicial' => 'nullable|numeric|required',
        'l_45_incremental' => 'nullable|numeric|required',
        'l_45_inicial' => 'nullable|numeric|required',
        'l_50_incremental' => 'nullable|numeric|required',
        'l_50_inicial' => 'nullable|numeric|required',
        'l_55_incremental' => 'nullable|numeric|required',
        'l_55_inicial' => 'nullable|numeric|required',
        'l_60_incremental' => 'nullable|numeric|required',
        'l_60_inicial' => 'nullable|numeric|required',
        'l_65_incremental' => 'nullable|numeric|required',
        'l_65_inicial' => 'nullable|numeric|required',
        'l_70_incremental' => 'nullable|numeric|required',
        'l_70_inicial' => 'nullable|numeric|required',
        'l_75_incremental' => 'nullable|numeric|required',
        'l_75_inicial' => 'nullable|numeric|required',
        'l_80_incremental' => 'nullable|numeric|required',
        'l_80_inicial' => 'nullable|numeric|required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function tablaAcopiadoraDetalles(){
        return $this->hasMany(TablaAcopiadoraDetalle::class)->orderBy('id');
    }
}
