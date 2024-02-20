<?php

namespace App\Models;

use App\Http\Controllers\CajaController;
use App\Http\Controllers\MovimientoController;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Movimiento extends Model
{
    use HasFactory;
    public $table = 'movimiento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'motivo',
        'tipo',
        'monto',
        'es_cancelado',
        'es_aprobado',
        'proveedor_id',
        'user_id',
        'factura',
        'oficina',
        'origen_id',
        'origen_type'
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'motivo' => 'string',
        'tipo' => 'string',
        'total' => 'string',
        'factura' => 'string',
        'es_cancelado' => 'string',
        'origen_id' => 'integer',
        'origen_type' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'motivo' => 'required|string|max:100',
        'tipo' => 'required',
        'total' => 'required|string|max:15',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function getSaldoPagoAttribute()
    {
        $obj = new MovimientoController();
        $saldo = $this->total - $obj->getSumaMontos($this->id);
        return $saldo;
    }

    public function getCodigoCajaAttribute()
    {
        $codigo = '';

        $pago = PagoMovimiento::whereOrigenType(Movimiento::class)->whereOrigenId($this->id)->first();
        if ($pago)
            $codigo= $pago->codigo;

        return $codigo;
    }

    public function getAltaAttribute()
    {
        $alta = true;

        $pago = PagoMovimiento::whereOrigenType(Movimiento::class)->whereOrigenId($this->id)->first();
        if ($pago)
            $alta= $pago->alta;

        return $alta;
    }
    public function getPagoIdAttribute()
    {
        $id = '';

        $pago = PagoMovimiento::whereOrigenType(Movimiento::class)->whereOrigenId($this->id)->first();
        if ($pago)
            $id= $pago->id;

        return $id;
    }

    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function autorizado()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function origen()
    {
        return $this->morphTo();
    }
}
