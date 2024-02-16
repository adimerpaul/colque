<?php

namespace App\Models;

use Eloquent as Model;


class LaboratorioQuimico extends Model
{
    public $table = 'laboratorio_quimico';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'nombre',
        'direccion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'direccion' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

}
