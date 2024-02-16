<?php

namespace App\Models;

use Eloquent as Model;


class Empresa extends Model
{
    public $table = 'empresa';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'identificacion_tributaria',
        'razon_social',
        'direccion',
        'email',
        'telefono',
        'celular',
        'alta',
        'logo',
        'cantidad_usuario',
        'membrete'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'identificacion_tributaria' => 'integer',
        'razon_social' => 'string',
        'direccion' => 'string',
        'email' => 'string',
        'telefono' => 'string',
        'celular' => 'string',
        'alta' => 'boolean',
        'logo' => 'string',
        'membrete' => 'string',
        'cantidad_usuario' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'identificacion_tributaria' => 'required|numeric|max:9999999999',
        'razon_social' => 'required|string|max:150',
        'direccion' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:100',
        'telefono' => 'nullable|string|max:30',
        'celular' => 'nullable|string|max:30',
        'alta' => 'required|boolean',
        'logo' => 'nullable|string|max:255',
        'membrete' => 'nullable|string|max:255',
        'cantidad_usuario' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function getInfoAttribute(){
        return sprintf("%s | %s", $this->identificacion_tributaria, $this->razon_social);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function personals()
    {
        return $this->hasMany(\App\Models\Personal::class, 'empresa_id');
    }
}
