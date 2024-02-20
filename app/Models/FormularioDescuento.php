<?php

namespace App\Models;

use App\Patrones\EnFuncion;
use App\Patrones\UnidadDescuentoBonificacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioDescuento extends Model
{
    use HasFactory;

    public $table = 'formulario_descuento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'formulario_liquidacion_id',
        'descuento_bonificacion_id',
        'valor',
        'en_funcion',
        'unidad'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at', 'updated_at'];


    protected $appends = ['sub_total'];
//    public function getSubTotalAttribute()
//    {
//        $subTotal = 0;
//        if($this->descuentoBonificacion->unidad === UnidadDescuentoBonificacion::Porcentaje) {
//            switch ($this->descuentoBonificacion->en_funcion) {
//                case EnFuncion::ValorNetoVenta:
//                    $subTotal = $this->formulario->valorNetoVenta * ($this->descuentoBonificacion->valor / 100);
//                    break;
//                case EnFuncion::ValorBrutoVenta:
//                    $subTotal = $this->formulario->pesoNetoSeco * ($this->descuentoBonificacion->valor / 100);
//                    break;
//                case EnFuncion::PesoNetoSeco:
//                    $subTotal = ($this->formulario->pesoNetoSeco / 1000) * $this->formulario->tipoCambio->dolar_compra * $this->descuentoBonificacion->valor;
//                    break;
//                case EnFuncion::Total:
//                    $subTotal = 0;
//                    break;
//            }
//        }
//        elseif($this->descuentoBonificacion->unidad === UnidadDescuentoBonificacion::Cantidad AND
//            $this->descuentoBonificacion->en_funcion === EnFuncion::Sacos) {
//            $subTotal = $this->formulario->sacos * $this->descuentoBonificacion->valor;
//        }
//
//        elseif($this->descuentoBonificacion->unidad === UnidadDescuentoBonificacion::DolarPorTonelada) {
//            $subTotal = ($this->formulario->pesoNetoSeco / 1000) * $this->formulario->tipoCambio->dolar_compra* $this->descuentoBonificacion->valor;
//        }
//
//        else
//            $subTotal = $this->descuentoBonificacion->valor;
//
//        return $subTotal;
//    }

    public function getSubTotalAttribute()
    {
        $subTotal = 0;
        if($this->unidad === UnidadDescuentoBonificacion::Porcentaje) {
            switch ($this->en_funcion) {
                case EnFuncion::ValorNetoVenta:
                    $subTotal = $this->formulario->valorNetoVenta * ($this->valor / 100);
                    break;
                case EnFuncion::ValorBrutoVenta: //TODO: el valor bruto de venta se calcula por mineral, si hay varios minerales no jala (regalia minera)
                    $subTotal = $this->formulario->pesoNetoSeco * ($this->valor / 100);
                    break;
                case EnFuncion::PesoNetoSeco:
                    $subTotal = ($this->formulario->pesoNetoSeco / 1000) * $this->formulario->tipoCambio->dolar_compra * $this->valor;
                    break;
                case EnFuncion::Total:
                    $subTotal = 0;
                    break;
            }
        }
        elseif($this->unidad === UnidadDescuentoBonificacion::Cantidad AND
            $this->en_funcion === EnFuncion::Sacos) {
            $subTotal = $this->formulario->sacos * $this->valor;
        }

        elseif($this->unidad === UnidadDescuentoBonificacion::DolarPorTonelada) {
            $subTotal = ($this->formulario->pesoNetoSeco / 1000) * $this->formulario->tipoCambio->dolar_compra* $this->valor;
        }

        else
            $subTotal = intval( $this->valor);

        return $subTotal;
    }

    public function formulario()
    {
        return $this->belongsTo(FormularioLiquidacion::class, 'formulario_liquidacion_id', 'id');
    }

    public function descuentoBonificacion()
    {
        return $this->belongsTo(DescuentoBonificacion::class);
    }
}
