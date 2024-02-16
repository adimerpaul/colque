<?php

namespace App\Models;

use Eloquent as Model;


class TipoTabla extends Model
{

    public $table = 'tipo_tabla';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'tabla',
        'valor',
    ];



    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tabla' => 'required|min:2|max:100',
        'valor' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


}
