<?php

namespace App\Models;

use Eloquent as Model;

class UnidadCotizacion extends Model
{
    public $table = 'unidad_cotizacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [

        'diaria',
        'oficial',
        'empresa_mineral_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'diaria' => 'string',
        'oficial' => 'string',
        'empresa_mineral_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'diaria' => 'required',
        'oficial' => 'required',
    ];


}
