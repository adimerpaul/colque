<?php

namespace App\Models;

use App\Http\Controllers\ApiDescuentoBonificacionController;
use App\Http\Controllers\BonoController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\LiquidacionMineralController;
use App\Patrones\Estado;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioLiquidacionData extends Model
{
    public $table = 'formulario_liquidacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'sigla',
        'numero_lote',
        'letra',
        'anio',
        'fecha_cotizacion',
        'fecha_liquidacion',
        'fecha_pesaje',
        'producto',
        'peso_bruto',
        'tara',
        'merma',
        'valor_por_tonelada',
        'estado',
        'observacion',
        'numero_tornaguia',
        'url_documento',
        'sacos',
        'saldo_favor',
        'saldo_favor',
        'tipo_cambio_id',
        'cliente_id',
        'chofer_id',
        'vehiculo_id',
        'es_cancelado',
        'fecha_cancelacion',
        'comision_externa',
        'tipo_material'

    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at', 'updated_at'];


    public $appends = [
        'lote',
        'es_escritura',
        'only_read',
        'peso_neto',
        'cantidad_devoluciones'
    ];

    public function getLoteAttribute()
    {
        return sprintf("%s%d%s/%s", $this->sigla, $this->numero_lote, $this->letra, substr($this->anio, 2, 2));
    }

    public function getEsEscrituraAttribute()
    {
        return $this->estado === Estado::EnProceso;
    }

    public function getOnlyReadAttribute()
    {
        return $this->estado === Estado::EnProceso ? "" : "pointer-events: none;";
    }

    public function getPesoNetoAttribute()
    {
        return $this->peso_bruto - $this->tara;
    }

    public function getCantidadDevolucionesAttribute()
    {
        $obj = new BonoController();
        return $obj->getCantidadDevoluciones($this->id);
    }

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id');
    }
}
