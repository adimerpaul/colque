<?php

namespace App\Models;

use App\Http\Controllers\MovimientoController;
use App\Patrones\Banco;
use App\Patrones\TipoPago;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class PagoMovimiento extends Model
{
    use HasFactory;

    public $table = 'pago_movimiento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'codigo',
        'monto',
        'glosa',
        'metodo',
        'alta',
        'motivo_anulacion',
        'anio',
        'banco',
        'numero',
        'origen_id',
        'origen_type'
//        ,
//        'saldo_caja',
//        'saldo_banco'
    ];

    public $hidden = ['updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo' => 'string',
        'monto' => 'string',
        'glosa' => 'string',
        'origen_id' => 'integer',
        'origen_type' => 'string',
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

    public function getSaldoActualAttribute()
    {
        $saldo = 0;
        if ($this->origen_type == Movimiento::class) {
            $movimiento = Movimiento::find($this->origen_id);
            $saldo = $movimiento->total - (PagoMovimiento::where('created_at', '<=', $this->created_at)
                    ->whereOrigenId($this->origen_id)
                    ->whereAlta(true)
                    ->whereOrigenType(Movimiento::class)
                    ->get()->sum('monto'));
        }
        return $saldo;
    }

    public function getSaldoPagoAttribute()
    {
        return PagoMovimiento::where('created_at', '<=', $this->created_at)->whereAlta(true)->whereMetodo(TipoPago::Efectivo)
            ->get()->sum('monto_signo');
    }

    public function getSaldoPagoBancoAttribute()
    {
        return PagoMovimiento::where('created_at', '<=', $this->created_at)->whereAlta(true)->whereMetodo(TipoPago::CuentaBancaria)
            ->get()->sum('monto_signo');
    }


    public function getSaldoCajaAttribute()
    {
        $fecha = date('Y-m-d', strtotime($this->created_at));
        $primerPago = PagoMovimiento::where(DB::raw("DATE(created_at)"), $fecha)->whereMetodo(TipoPago::Efectivo)->whereAlta(true)
            ->orderBy('numero')->first();

        $obj = new MovimientoController();
        $saldoInicial = $obj->getSaldo($fecha, TipoPago::Efectivo);

        if ($primerPago) {
            if ($primerPago->id == $this->id) {
                return ($saldoInicial + $this->monto_signo);
            } else {
                $sumaHoy = PagoMovimiento::where('numero', '<=', $this->numero)->where(DB::raw("DATE(created_at)"), $fecha)
                    ->whereAlta(true)->whereMetodo(TipoPago::Efectivo)
                    ->get()->sum('monto_signo');
                return ($sumaHoy + $saldoInicial);
            }
        } else {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldo($fecha, TipoPago::Efectivo);
            return ($saldoInicial);
        }

    }

    public function getSaldoBancoAttribute()
    {
        $fecha = date('Y-m-d', strtotime($this->created_at));
        $primerPago = PagoMovimiento::where(DB::raw("DATE(created_at)"), $fecha)->whereMetodo(TipoPago::CuentaBancaria)->whereAlta(true)
            ->orderBy('created_at')->first();

        if ($primerPago) {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldo($fecha, TipoPago::CuentaBancaria);

            if ($primerPago->id == $this->id) {
                return ($saldoInicial + $this->monto_signo);
            } else {
                $sumaHoy = PagoMovimiento::where('numero', '<=', $this->numero)->where(DB::raw("DATE(created_at)"), $fecha)
                    ->whereAlta(true)->whereMetodo(TipoPago::CuentaBancaria)
                    ->get()->sum('monto_signo');
                return ($sumaHoy + $saldoInicial);
            }
        } else {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldo($fecha, TipoPago::CuentaBancaria);
            return ($saldoInicial);
        }
    }

    public function getSaldoBnbAttribute()
    {
        $fecha = date('Y-m-d', strtotime($this->created_at));

        $primerPago = PagoMovimiento::where(DB::raw("DATE(created_at)"), $fecha)
            ->whereMetodo(TipoPago::CuentaBancaria)->whereAlta(true)->whereBanco(Banco::BNB)
            ->orderBy('created_at')->first();

        if ($primerPago) {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldo($fecha, TipoPago::CuentaBancaria, Banco::BNB);

            if ($primerPago->id == $this->id) {
                return ($saldoInicial + $this->monto_signo);
            } else {
                $sumaHoy = PagoMovimiento::where('numero', '<=', $this->numero)->where(DB::raw("DATE(created_at)"), $fecha)
                    ->whereAlta(true)->whereMetodo(TipoPago::CuentaBancaria)->whereBanco(Banco::BNB)
                    ->get()->sum('monto_signo');
                return ($sumaHoy + $saldoInicial);
            }
        } else {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldo($fecha, TipoPago::CuentaBancaria, Banco::BNB);
            return ($saldoInicial);
        }
    }

    public function getSaldoEconomicoAttribute()
    {
        $fecha = date('Y-m-d', strtotime($this->created_at));
        $primerPago = PagoMovimiento::where(DB::raw("DATE(created_at)"), $fecha)->whereMetodo(TipoPago::CuentaBancaria)->whereAlta(true)
            ->where(function ($q)  {
                $q->whereBanco(Banco::Economico)->orWhere('banco', 'Fortaleza');
            })
            ->orderBy('created_at')->first();

        if ($primerPago) {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldo($fecha, TipoPago::CuentaBancaria, Banco::Economico);

            if ($primerPago->id == $this->id) {
                return ($saldoInicial + $this->monto_signo);
            } else {
                $sumaHoy = PagoMovimiento::where('numero', '<=', $this->numero)->where(DB::raw("DATE(created_at)"), $fecha)
                    ->whereAlta(true)->whereMetodo(TipoPago::CuentaBancaria)
                    ->where(function ($q)  {
                        $q->whereBanco(Banco::Economico)->orWhere('banco', 'Fortaleza');
                    })
                    ->get()->sum('monto_signo');
                return ($sumaHoy + $saldoInicial);
            }
        } else {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldo($fecha, TipoPago::CuentaBancaria, Banco::Economico);
            return ($saldoInicial);
        }
    }

     public function getMontoSignoAttribute()
     {
         $monto = $this->monto;
         if ($this->origen->tipo === 'Egreso') {
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
    public function getMontoBnbAttribute()
    {
        $monto = '';
        if ($this->metodo == TipoPago::CuentaBancaria and $this->banco==Banco::BNB) {
            $monto = $this->monto;
        }
        return $monto;
    }
    public function getMontoEconomicoAttribute()
    {
        $monto = '';
        if ($this->metodo == TipoPago::CuentaBancaria and ($this->banco==Banco::Economico or $this->banco=='Fortaleza')) {
            $monto = $this->monto;
        }
        return $monto;
    }

    public function getTotalDevolucionAttribute()
    {
        $total = '';
        if ($this->origen_type == Bono::class)
            $total = $this->origen->total;
        return $total;
    }

    public function getFacturaAttribute(){
        $factura='';
        if($this->origen_type==Movimiento::class){
            $factura= $this->origen->factura;
        }
        return $factura;
    }

    public function getEmpresaAttribute()
    {
        $empresa = '';
        switch ($this->origen_type) {
            case Movimiento::class:
                $empresa = $this->origen->proveedor->empresa;
                break;
            case FormularioLiquidacion::class:
                $empresa = $this->origen->cliente->cooperativa->razon_social;
                break;
            case CuentaCobrar::class:
                $empresa = $this->origen->origen->cooperativa->razon_social;
                break;
            case Bono::class:
                $empresa = $this->origen->formularioLiquidacion->cliente->cooperativa->razon_social;
                break;
            case Anticipo::class:
                $empresa = $this->origen->formularioLiquidacion->cliente->cooperativa->razon_social;
                break;
            case Prestamo::class:
                $empresa = $this->origen->cliente->cooperativa->razon_social;
                break;

            case Venta::class:
                $empresa = $this->origen->comprador->razon_social;
                break;

            case AnticipoVenta::class:
                $empresa = $this->origen->venta->comprador->razon_social;
                break;

            case PagoRetencion::class:
                $empresa = $this->origen->cooperativa;
                break;
            default:
                $empresa = $this->origen->cooperativa->razon_social;
        }
        return $empresa;
    }

    public function getClienteAttribute()
    {
        $empresa = '';
        switch ($this->origen_type) {
            case Movimiento::class:
                $empresa = $this->origen->proveedor->nombre;
                break;
            case FormularioLiquidacion::class:
                $empresa = $this->origen->cliente->nombre;
                break;
            case CuentaCobrar::class:
                $empresa = $this->origen->origen->nombre;
                break;
            case Bono::class:
                $empresa = $this->origen->formularioLiquidacion->cliente->nombre;
                break;
            case Anticipo::class:
                $empresa = $this->origen->formularioLiquidacion->cliente->nombre;
                break;
            case Prestamo::class:
                $empresa = $this->origen->cliente->nombre;
                break;
            case Venta::class:
                $empresa = '';
                break;
            case AnticipoVenta::class:
                $empresa = '';
                break;
            case PagoRetencion::class:
                $empresa = '';
                break;
        }
        return $empresa;
    }

//    public function movimiento(){
//        return $this->belongsTo(Movimiento::class);
//    }


    public function origen()
    {
        return $this->morphTo();
    }
}
