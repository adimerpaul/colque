<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Comprador extends Model
{
    use HasFactory;

    public $table = 'comprador';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'razon_social',
        'nro_nim',
        'nit',
        'direccion',
        'es_aprobado'
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'razon_social' => 'string',
        'nro_nim' => 'string',
        'nit' => 'string',
        'direccion' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'razon_social' => 'required|min:2|max:100',
        'nro_nim' => 'required|string',
        'nit' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


    public static function rules($isNew = true)
    {
        return [
            'razon_social' => 'required|min:2|max:100',
            'nro_nim' => 'required|min:5|max:11' . ($isNew ? '|unique:comprador' : ''),
            'nit' => 'required|min:5|max:20' . ($isNew ? '|unique:comprador' : ''),
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }

    public $appends = ['info'];

    public function getInfoAttribute()
    {
        return sprintf("%s | %s", $this->nro_nim, $this->razon_social);
    }

}
