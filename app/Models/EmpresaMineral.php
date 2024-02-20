<?php

namespace App\Models;

use Eloquent as Model;


class EmpresaMineral extends Model
{
    public $table = 'empresa_mineral';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'mineral_id',
        'empresa_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mineral_id' => 'integer',
        'empresa_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($isNew = true)
    {
        return [
            'mineral_id' => 'required',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }

}
