<?php

namespace App\Models;

use Eloquent as Model;


class LaboratorioPrecio extends Model
{
    public $table = 'laboratorio_precio';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'monto',
        'laboratorio_quimico_id',
        'producto_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'monto' => 'decimal:2',
        'laboratorio_quimico_id' => 'integer',
        'producto_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'monto' => 'required|numeric|min:0.01|max:999.99',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'producto_id');
    }

    public function laboratorioQuimico()
    {
        return $this->belongsTo(\App\Models\LaboratorioQuimico::class, 'laboratorio_quimico_id');
    }
}
