<?php

namespace App\Models;

use App\Http\Controllers\CajaController;
use App\Patrones\Banco;
use App\Patrones\TipoPago;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Prestamo extends Model
{
    use HasFactory;

    public $table = 'prestamo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'tipo',
        'monto',
        'es_cancelado',
        'cliente_id',
        'user_id',
        'aprobado',
        'motivo',
        'aprobado_id'
    ];

//    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tipo' => 'string',
        'monto' => 'string',

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'monto' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function getCodigoCajaAttribute()
    {
        $obj = new CajaController();
        $codigo = '';

        $pago = PagoMovimiento::whereOrigenType(Prestamo::class)->whereOrigenId($this->id)->first();
        if ($pago)
            $codigo= $pago->codigo;

        return $codigo;
    }

    public function getMetodoPagoAttribute()
    {
        $tipo='';
        $pago = PagoMovimiento::whereOrigenType(Prestamo::class)->whereOrigenId($this->id)->first();
        if ($pago->metodo==TipoPago::Efectivo)
            $tipo = TipoPago::Efectivo;
        else{
            if ($pago->banco==Banco::BNB)
                $tipo=Banco::BNB;
            else
                $tipo=Banco::Economico;
        }
        return $tipo;
    }

    public function getAltaAttribute()
    {
        $alta=true;
        $pago = PagoMovimiento::whereOrigenType(Prestamo::class)->whereOrigenId($this->id)->first();
        if ($pago)
            $alta = $pago->alta;
        return $alta;
    }

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id');
    }

    public function registrado()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function aprobadoPor()
    {
        return $this->belongsTo(\App\Models\User::class, 'aprobado_id');
    }
}
