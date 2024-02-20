<?php

namespace App\Models;

use App\Http\Controllers\BonoController;
use App\Http\Controllers\CajaController;
use App\Patrones\ClaseDevolucion;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Bono extends Model
{
    use HasFactory;

    public $table = 'bono';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'fecha',
        'monto',
        'motivo',
        'es_cancelado',
        'tipo',
        'clase',
        'tipo_motivo',
        'formulario_liquidacion_id',
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha' => 'required',
        'monto' => 'required|min:0.10|max:99999.99',
        'motivo' => 'required|min:2|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public $hidden = ['created_at', 'updated_at'];

    public $appends = [
        'causa'
    ];

    public function getCodigoCajaAttribute()
    {
        $obj = new CajaController();
        $codigo = '';
        if($this->es_cancelado)
            $codigo=$obj->getCodigoCaja($this->id, Bono::class);
        return $codigo;
    }

    public function getTotalAttribute(){
        $obj = new BonoController();
        $total = $obj->getTotal($this->formulario_liquidacion_id);
        return $total;
    }

    public function getCausaAttribute(){
        $causa = 'Transferido de otro lote';
        if($this->clase == ClaseDevolucion::Interno)
            $causa = 'Retiro de material';
        elseif($this->clase == ClaseDevolucion::Analisis)
            $causa = 'Análisis de laboratorio';

        return $causa;

    }

    public function formularioLiquidacion()
    {
        return $this->belongsTo(FormularioLiquidacion::class);
    }

    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }
}
