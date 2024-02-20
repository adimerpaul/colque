<?php

namespace App\Models;
use App\Models\Rrhh\Asistencia;

use Eloquent as Model;

class Personal extends Model
{
    public $table = 'personal';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'ci',
        'ci_add',
        'expedido',
        'nombre_completo',
        'celular',
        'firma',
        'empresa_id',
        'es_jefe',
        'cargo',
        'biometrico',
        'superior_id',
        'fecha_nacimiento',
        'fecha_ingreso',
        'tipo_contrato',
        'haber_basico',
        'nacionalidad',
        'sexo'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ci' => 'string',
        'ci_add' => 'string',
        'expedido' => 'string',
        'nombre_completo' => 'string',
        'telefono' => 'string',
        'celular' => 'string',
        'firma' => 'string',
        'empresa_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ci' => 'required|string|max:20',
        'ci_add' => 'nullable|string|max:10',
        'expedido' => 'required|string|max:30',
        'nombre_completo' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:30',
        'celular' => 'nullable|string|max:30',
        'empresa_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class, 'empresa_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'personal_id');
    }





}
