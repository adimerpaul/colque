<?php

namespace App\Models;

use Eloquent as Model;


class Ley extends Model
{
    public $table = 'ley_precio';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'valor',
        'unidad',
        'material_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'valor' => 'decimal:2',
        'unidad' => 'string',
        'material_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'valor' => 'required|numeric|min:0.01|max:9.99',
        'unidad' => 'required|string',
    //    'material_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function material()
    {
        return $this->belongsTo(\App\Models\Material::class, 'material_id');
    }
}
