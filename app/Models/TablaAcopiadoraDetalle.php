<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TablaAcopiadoraDetalle extends Model
{
    use HasFactory;

    public $table = 'tabla_acopiadora_detalle';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'cotizacion',
        'l_0',
        'l_5',
        'l_10',
        'l_15',
        'l_20',
        'l_25',
        'l_30',
        'l_35',
        'l_40',
        'l_45',
        'l_50',
        'l_55',
        'l_60',
        'l_65',
        'l_70',
        'l_75',
        'l_80',
        'tabla_acopiadora_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'cotizacion' => 'float',
        'l_0' => 'float',
        'l_5' => 'float',
        'l_10' => 'float',
        'l_15' => 'float',
        'l_20' => 'float',
        'l_25' => 'float',
        'l_30' => 'float',
        'l_35' => 'float',
        'l_40' => 'float',
        'l_45' => 'float',
        'l_50' => 'float',
        'l_55' => 'float',
        'l_60' => 'float',
        'l_65' => 'float',
        'l_70' => 'float',
        'l_75' => 'float',
        'l_80' => 'float',
        'tabla_acopiadora_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cotizacion' => 'required|numeric',
        'l_0' => 'nullable|numeric',
        'l_5' => 'nullable|numeric',
        'l_10' => 'nullable|numeric',
        'l_15' => 'nullable|numeric',
        'l_20' => 'nullable|numeric',
        'l_25' => 'nullable|numeric',
        'l_30' => 'nullable|numeric',
        'l_35' => 'nullable|numeric',
        'l_40' => 'nullable|numeric',
        'l_45' => 'nullable|numeric',
        'l_50' => 'nullable|numeric',
        'l_55' => 'nullable|numeric',
        'l_60' => 'nullable|numeric',
        'l_65' => 'nullable|numeric',
        'l_70' => 'nullable|numeric',
        'l_75' => 'nullable|numeric',
        'l_80' => 'nullable|numeric',

        'tabla_acopiadora_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tablaAcopiadora()
    {
        return $this->belongsTo(\App\Models\TablaAcopiadora::class, 'tabla_acopiadora_id');
    }
}
