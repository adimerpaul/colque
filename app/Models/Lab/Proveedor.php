<?php

namespace App\Models\Lab;

use Eloquent as Model;

class Proveedor extends Model
{
    public $table = 'laboratorio.proveedor';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nit',
        'nombre',
        //'observacion'
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
      //  'observacion' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nit' => 'digits_between:5,15',
        'nombre' => 'required|min:2|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


    public $appends = ['info'];
    public function getInfoAttribute(){
        return sprintf("%s <span class='pull-right'>%s</span>", $this->nit, $this->nombre);
    }

    public function getInfoProveedorAttribute(){
        return sprintf("%s | %s", $this->nombre, $this->nit);
    }

    public function getInformacionAttribute(){
        return sprintf("%s <br><small class='text-muted'>Nit: %s</small>", $this->nombre, $this->nit);
    }
}
