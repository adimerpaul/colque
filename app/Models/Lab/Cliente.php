<?php

namespace App\Models\Lab;
use Eloquent as Model;
class Cliente extends Model
{
    public $table = 'laboratorio.cliente';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nit',
        'complemento',
        'nombre',
        'celular',
        'direccion'

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
        'celular' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nit' => 'required|min:5|max:20',
        'nombre' => 'required|min:2|max:100',
        'celular' => 'digits_between:8,8',
        'direccion' => 'required|string|min:5|max:150',
    ];




    public $appends = ['info', 'info_cliente'];

    public function getInfoAttribute(){
            return sprintf("%s <br><small class='text-muted'>Nit: %s</small>", $this->nombre, $this->carnet);
    }


    public function getInfoClienteAttribute(){
        return sprintf("%s | %s", $this->nombre, $this->carnet);
    }

    public function getCarnetAttribute(){
        return sprintf("%s%s", $this->nit, $this->complemento);
    }



}

