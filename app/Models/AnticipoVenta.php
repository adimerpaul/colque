<?php

namespace App\Models;

use App\Http\Controllers\CajaController;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AnticipoVenta extends Model
{
    use HasFactory;

    public $table = 'anticipo_venta';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'motivo',
        'monto',
        'venta_id',
        'tipo',
        'es_cancelado'
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'motivo' => 'max:100',
        'monto' => 'required|min:0.10|max:99999.99',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

//    public $hidden = ['created_at', 'updated_at'];

    public $appends = ['codigo_caja'];



    public function getCodigoCajaAttribute()
    {
        $obj = new CajaController();
        $codigo = '';
        if($this->es_cancelado)
            $codigo=$obj->getCodigoCaja($this->id, AnticipoVenta::class);
        return $codigo;
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }
}
