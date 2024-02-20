<?php

namespace App\Models;

use App\Http\Controllers\CajaController;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Anticipo extends Model
{
    use HasFactory;

    public $table = 'anticipo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'motivo',
        'fecha',
        'monto',
        'formulario_liquidacion_id',
        'tipo',
        'es_cancelado',
        'anticipo_pago',
        'cliente_pago'
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'motivo' => 'max:100',
        'fecha' => 'required',
        'monto' => 'required|min:0.10|max:99999.99',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public $hidden = ['created_at', 'updated_at'];

    public $appends = ['fecha_formato', 'codigo_caja'];

    public function getFechaFormatoAttribute()
    {
        return date('d/m/Y', strtotime($this->fecha));
    }

    public function getCodigoCajaAttribute()
    {
        $obj = new CajaController();
        $codigo = '';
        if($this->es_cancelado)
            $codigo=$obj->getCodigoCaja($this->id, Anticipo::class);
        return $codigo;
    }



    public function formularioLiquidacion()
    {
        return $this->belongsTo(FormularioLiquidacion::class);
    }
    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }

    public function clientePago()
    {
        return $this->belongsTo(Cliente::class, 'cliente_pago');
    }
}
