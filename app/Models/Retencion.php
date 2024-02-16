<?php

namespace App\Models;

use App\Http\Controllers\CajaController;
use App\Http\Controllers\MovimientoController;
use App\Patrones\Estado;
use App\Patrones\Fachada;
use App\Patrones\TipoDescuentoBonificacion;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Retencion extends Model
{
    use HasFactory;
    public $table = 'retencion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'tipo',
        'monto',
        'motivo',
        'nombre',
        'es_aprobado',
        'cooperativa_id'
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
        'nombre' => 'string',
        'cooperativa_id' => 'integer',
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
            $codigo=$obj->getCodigoCaja($this->id, Retencion::class);
        return $codigo;
    }

    public function getQuincenaAttribute()
    {

        if(date("d", strtotime($this->created_at))>15){
            $numeroQuincena = '2da quincena';
        } else {
            $numeroQuincena = '1ra quincena';
        }
        $mes = Fachada::getMesEspanol(date("n", strtotime($this->created_at))); //strftime("%B");
        return ( $numeroQuincena . ' de ' . $mes);
    }

    public function getFechaFinalAttribute()
    {
        if(date("d", strtotime($this->created_at))>15)
        {
            return (date("t/m/Y", strtotime($this->created_at)));
        }
        else{
            return (date("15/m/Y", strtotime($this->created_at)));
        }
    }
    public function getFechaFinAttribute()
    {
        if(date("d", strtotime($this->created_at))>15)
        {
            return (date("Y-m-t", strtotime($this->created_at)));
        }
        else{
            return (date("Y-m-15", strtotime($this->created_at)));
        }
    }

    public function getFechaHoraInicioAttribute()
    {
        if(date("d", strtotime($this->created_at))>15)
        {
            return (date("Y-m-16 00:00:00", strtotime($this->created_at)));
        }
        else{
            return (date("Y-m-01 00:00:00", strtotime($this->created_at)));
        }
    }

    public function getFechaHoraFinAttribute()
    {
        if(date("d", strtotime($this->created_at))>15)
        {
            return (date("Y-m-t 23:59:59", strtotime($this->created_at)));
        }
        else{
            return (date("Y-m-15 23:59:59", strtotime($this->created_at)));
        }
    }

    public function getMontoFinalAttribute()
    {
        $nombre = $this->nombre;
        $total=0;
        $descuentosFormulario = FormularioDescuento::
            whereHas('formulario', function ($q){
                $q->where('fecha_hora_liquidacion', '>=', $this->fecha_hora_inicio)
                    ->where('fecha_hora_liquidacion', '<=', $this->fecha_hora_fin)
                    ->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido]);
            })
            ->whereHas('descuentoBonificacion', function ($q) use ($nombre) {
                $q->where('nombre', $nombre)->whereCooperativaId($this->cooperativa_id);
            })
            ->get();
        foreach ($descuentosFormulario as $descuento) {
            $total = $total + $descuento->sub_total;
        }
        return $total;
    }

    public function cooperativa(){
        return $this->belongsTo(Cooperativa::class);
    }
}
