<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Proveedor extends Model
{
    use HasFactory;
    public $table = 'proveedor';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nit',
        'nombre',
        'empresa',
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
        'nit' => 'string',
        'nombre' => 'string',
        'empresa' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($isNew = true)
    {
        return [
            'nit' => 'digits_between:5,15'.($isNew ? '|unique:proveedor' : ''),
            'nombre' => 'required|min:2|max:100',
            'empresa' => 'required|min:2|max:100',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }


    public $appends = ['info_proveedor'];
    public function getInfoAttribute(){
        return sprintf("%s | %s <span class='pull-right'>%s</span>", $this->nit, $this->nombre, $this->empresa);
    }

    public function getInfoProveedorAttribute(){
        return sprintf("%s | %s <br><small class='text-muted'>Empresa: %s</small>", $this->nit, $this->nombre, $this->empresa);
    }
}
