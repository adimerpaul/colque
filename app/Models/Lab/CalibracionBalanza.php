<?php

namespace App\Models\Lab;

use Eloquent as Model;

class CalibracionBalanza extends Model
{
    public $table = 'laboratorio.calibracion_balanza';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'valor',
        'tipo',
        'constante_medida_id'
    ];

    public function getDiferenciaAttribute(){
        return $this->constanteMedida->valor - $this->valor;
    }

    public function constanteMedida()
    {
        return $this->belongsTo(ConstanteMedida::class);
    }
}
