<?php

namespace App\Models\Lab;

use Eloquent as Model;

class ConstanteMedida extends Model
{
    public $table = 'laboratorio.constante_medida';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'valor',
        'tipo',
    ];

    public function getInfoAttribute(){
        return sprintf("%s (%s)", $this->tipo, $this->valor);
    }
}
