<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TipoReporte extends Model
{
    use HasFactory;

    public $table = 'tipo_reporte';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'nombre',
        'descripcion',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'descripcion' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */


    public static function rules($isNew = true)
    {
        return [
            'nombre' => 'required'.($isNew ? '|unique:tipo_reporte' : ''),
            'descripcion' => 'required|min:2|max:300',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }

}
