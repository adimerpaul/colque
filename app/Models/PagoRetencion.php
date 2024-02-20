<?php

namespace App\Models;

use App\Http\Controllers\CajaController;
use App\Http\Controllers\MovimientoController;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PagoRetencion extends Model
{
    use HasFactory;
    public $table = 'pago_retencion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'tipo',
        'monto',
        'motivo',
        'es_cancelado',
        'retenciones_id',
        'es_aprobado'
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
            $codigo=$obj->getCodigoCaja($this->id, PagoRetencion::class);
        return $codigo;
    }


    public function getCooperativaAttribute(){
        $primero = strtok($this->retenciones_id, ',');
        $retencion = Retencion::find($primero);
        return $retencion->cooperativa->razon_social;
    }

    public function getQuincenasAttribute(){
        $quincena= "";
        $seleccionados = explode(",", $this->retenciones_id);
        $retencionesPagos= Retencion::whereIn('id', $seleccionados)->orderBy('created_at')->get();
        foreach($retencionesPagos as $ret){
            if(!str_contains($quincena, $ret->quincena))
            $quincena = $quincena . $ret->quincena . ', ';
        }
        return substr($quincena, 0, -2);
    }

    public function getNombresRetencionesAttribute(){
        $nombres= "";
        $seleccionados = explode(",", $this->retenciones_id);
        $retencionesPagos= Retencion::whereIn('id', $seleccionados)->orderBy('created_at')->get();
        foreach($retencionesPagos as $ret){
            if(!str_contains($nombres, $ret->nombre))
                $nombres = $nombres . $ret->nombre . ', ';
        }
        return substr($nombres, 0, -2);
    }

    public function getCooperativaIdAttribute(){
        $primero = strtok($this->retenciones_id, ',');
        $retencion = Retencion::find($primero);
        return $retencion->cooperativa_id;
    }


    public function getAltaAttribute()
    {
        $alta=true;
        $pago = PagoMovimiento::whereOrigenType(PagoRetencion::class)->whereOrigenId($this->id)->first();
        if ($pago)
            $alta = $pago->alta;
        return $alta;
    }
}
