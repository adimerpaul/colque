<?php

namespace App\Models\Lab;

use Eloquent as Model;

class Movimiento extends Model
{
    public $table = 'laboratorio.movimiento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
       // 'tipo',
        'proveedor_id',
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'proveedor_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

}

