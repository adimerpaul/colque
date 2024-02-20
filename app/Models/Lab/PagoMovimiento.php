<?php

namespace App\Models\Lab;

use App\Patrones\TipoPago;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Eloquent as Model;

class PagoMovimiento extends Model
{
    use HasFactory;

    public $table = 'laboratorio.pago_movimiento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'codigo',
        'monto',
        'glosa',
        'es_cancelado',
        'tipo',
        'metodo',
        'alta',
        'factura',
        'anio',
        'fecha',
        'comprobante_banco',
        'motivo_anulacion',
        'origen_id',
        'origen_type'
    ];

    public $hidden = ['updated_at'];

    public $appends = [
        'cantidad_ensayos'
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'monto' => 'required',
    ];


    public function getMontoSignoAttribute()
    {
        $monto = $this->monto;
        if ($this->tipo === 'Egreso') {
            $monto = $monto * (-1);
        }
        return $monto;
    }

    public function getMontoCajaAttribute()
    {
        $monto = '';
        if ($this->metodo == TipoPago::Efectivo) {
            $monto = $this->monto;
        }
        return $monto;
    }

    public function getMontoBancoAttribute()
    {
        $monto = '';
        if ($this->metodo == TipoPago::CuentaBancaria) {
            $monto = $this->monto;
        }
        return $monto;
    }


    public function getClienteAttribute()
    {
        $empresa = '';
        switch ($this->origen_type) {
            case Movimiento::class:
                $empresa = $this->origen->proveedor->nombre;
                break;
            case Recepcion::class:
                $empresa = $this->origen->cliente->nombre;
                break;
        }
        return $empresa;
    }

    public function getClienteInfoAttribute()
    {
        $empresa = '';
        switch ($this->origen_type) {
            case Movimiento::class:
                $empresa = $this->origen->proveedor->informacion;
                break;
            case Recepcion::class:
                $empresa = $this->origen->cliente->info;
                break;
        }
        return $empresa;
    }

    public function getCodigoRecepcionAttribute()
    {
        $cod = '';
        switch ($this->origen_type) {
            case '\App\Models\Lab\Movimiento':
                $cod = '';
                break;
            case Recepcion::class:
                $cod = $this->origen->codigo_pedido;
                break;
        }
        return $cod;
    }

    public function getCantidadEnsayosAttribute()
    {
        $cantidad = 0;
//        if ($this->origen_type==Recepcion::class) {
//                $cantidad = $this->origen->cantidad;
//        }
        return $cantidad;
    }


    public function origen()
    {
        return $this->morphTo();
    }
}
