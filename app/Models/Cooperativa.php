<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cooperativa extends Model
{
    use HasFactory;
    public $table = 'cooperativa';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'razon_social',
        'nro_nim',
        'nit',
        'fecha_expiracion',
        'url_documento',
        'tipo_contrato',
        'municipio_id',
        'es_aprobado',
        'tipo',
        'es_finalizado',
        'user_registro_id'
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
        'tipo_contrato' => 'string',
        'fecha_expiracion' => 'date',
        'url_documento' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */

    public static function rules($isNew = true)
    {
        return [
            'razon_social' => 'required|min:2|max:100',
            'nro_nim' => 'required|string' . ($isNew ? '|unique:cooperativa' : ''),
            'nit' => 'digits_between:5,18' . ($isNew ? '|unique:cooperativa' : ''),
            'fecha_expiracion' => 'required',
            'tipo_contrato' => 'required',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }

    public $appends = ['info'];
    public function getInfoAttribute(){
        return sprintf("%s | %s", $this->nro_nim, $this->razon_social);
    }

    public function clientes(){
        return $this->hasMany(Cliente::class);
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class);
    }

    public function descuentoBonificaciones(){
        return $this->hasMany(DescuentoBonificacion::class);
    }
}
