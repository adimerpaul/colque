<?php

namespace App\Models;

use App\Http\Controllers\MovimientoController;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class PagoDolar extends Model
{
    use HasFactory;

    public $table = 'pago_dolar';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'codigo',
        'monto',
        'glosa',
        'factura',
        'anio',
        'tipo',
        'proveedor_id',
        'venta_id',
    ];

    public $hidden = ['updated_at'];



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


    public function getSaldoAttribute()
    {
        $fecha = date('Y-m-d', strtotime($this->created_at));
        $primerPago = PagoDolar::where(DB::raw("DATE(created_at)"), $fecha)
            ->orderBy('created_at')->first();

        if ($primerPago) {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldoDolares($fecha);

            if ($primerPago->id == $this->id) {
                return ($saldoInicial + $this->monto_signo);
            } else {
                $sumaHoy = PagoDolar::where('id', '<=', $this->id)->where(DB::raw("DATE(created_at)"), $fecha)
                    ->get()->sum('monto_signo');
                return ($sumaHoy + $saldoInicial);
            }
        } else {
            $obj = new MovimientoController();
            $saldoInicial = $obj->getSaldoDolares($fecha);
            return ($saldoInicial);
        }
    }

    public function getSaldoPagoAttribute()
    {
        return PagoDolar::where('created_at', '<=', $this->created_at)
            ->get()->sum('monto_signo');
    }

    public function getMontoSignoAttribute()
    {
        $pago = PagoDolar::find($this->id);
        $monto = $pago->monto;
        if ($pago->tipo === 'Egreso') {
            $monto = $monto * (-1);
        }
        return $monto;
    }

    public function getClienteAttribute(){
        if(!is_null($this->proveedor_id))
            return sprintf("%s | %s <br><small class='text-muted'>Empresa: %s</small>", $this->proveedor->nit, $this->nombre, $this->empresa);
        else
            return sprintf("%s | %s", $this->venta->comprador->nit, $this->venta->comprador->razon_social);

    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

}
