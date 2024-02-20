<?php

namespace App\Models;

use App\Http\Controllers\CajaController;
use App\Http\Controllers\MovimientoController;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\Fachada;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use function PHPUnit\Framework\isNull;


class CuentaCobrar extends Model
{
    use HasFactory;
    public $table = 'cuenta_cobrar';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'tipo',
        'monto',
        'motivo',
        'clase',
        'origen_id',
        'origen_type',
        'formulario_liquidacion_id',
        'prestamo_id',
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
        'motivo' => 'string',
        'clase' => 'string',
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
    public function getCodigoCajaAttribute()
    {
        $obj = new CajaController();
        $codigo = '';
        if($this->es_cancelado)
            $codigo=$obj->getCodigoCaja($this->id, CuentaCobrar::class);
        return $codigo;
    }

//    public function getPrestamoIdAttribute()
//    {
//        $prestamoId='';
//        if($this->clase==ClaseCuentaCobrar::Prestamo){
//            $codigo = str_replace('DEVOLUCIÓN POR PRÉSTAMO DE DINERO, COMPROBANTE: ', '', $this->motivo);
//
//            $prestamo = PagoMovimiento::whereCodigo($codigo)->whereOrigenType(Prestamo::class)->first();
//            $prestamoId = $prestamo->origen_id;
//        }
//        return $prestamoId;
//    }

    public function getPrestamoCodigoAttribute()
    {
        $codigo='';
        if($this->clase==ClaseCuentaCobrar::Prestamo){
            $prestamo = Prestamo::find($this->prestamo_id);
            $codigo = $prestamo->codigo_caja;
        }
        return $codigo;
    }

    public function getAnioAttribute()
    {
        $fechaActual = Fachada::getFecha();
        $fechaInicioGestion = \DateTime::createFromFormat('d/m/Y', date('d/m/Y',
            strtotime(date('Y') . "-10-01")));

        $anio = date('y');

        if ($fechaActual >= $fechaInicioGestion)
            $anio += 1;

        return $anio;
    }

    public function getIdInicioAttribute()
    {
        $id=0;
        if(!is_null($this->formulario_liquidacion_id))
            $id = $this->formulario_liquidacion_id;

        elseif(!is_null($this->prestamo_id))
            $id = $this->prestamo_id;

//        $motivo = explode(' ', $this->motivo);
//        $ultimaPalabra = array_pop($motivo);
//        $saldo = "SALDO";
//
//        $id='';
//        $contador = CuentaCobrar::whereId($this->id)->where('motivo', 'ilike', "%{$saldo}%")->count();
//        if ($contador >0){
//            $numero = substr($ultimaPalabra, 0, -2);
//            $numero = (int) filter_var($numero, FILTER_SANITIZE_NUMBER_INT);
//            $anio = '20'.(substr($ultimaPalabra, -2));
//            $letra = substr($ultimaPalabra, -4, 1);
//            $formulario =
//                FormularioLiquidacion::where('anio', $anio)->where('numero_lote', $numero)->where('letra', $letra)->first();
//            $id = $formulario->id;
//        }
//        else{
//            $pago = PagoMovimiento::whereAnio($this->anio)->whereCodigo($ultimaPalabra)->first();
//            $id = $pago->origen_id;
//        }

        return $id;
    }

    public function origen()
    {
        return $this->morphTo();
    }

    public function prestamo()
    {
        return $this->hasMany(\App\Models\Prestamo::class, 'prestamo_id');
    }

    public function formularioLiquidacion()
    {
        return $this->hasMany(\App\Models\FormularioLiquidacion::class, 'formulario_liquidacion_id');
    }
}
