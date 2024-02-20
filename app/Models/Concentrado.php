<?php


namespace App\Models;


use App\Patrones\TipoLoteVenta;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Concentrado extends Model
{
    use HasFactory;

    protected $table = 'concentrado';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'accion',
        'fecha',
        'nombre',
        'peso_bruto_humedo',
        'tara',
        'humedad',
        'valor_tonelada',
        'valor_neto_venta',
        'regalia_minera',
        'retenciones',
        'merma_porcentaje',
        'peso_neto_humedo',
        'ley_sn',
        'cotizacion_sn',
        'ley_zn',
        'cotizacion_zn',
        'ley_ag',
        'cotizacion_ag',
        'ley_pb',
        'cotizacion_pb',
        'ley_au',
        'cotizacion_au',
        'ley_sb',
        'cotizacion_sb',
        'ley_cu',
        'cotizacion_cu',
        'venta_id',
        'tipo_lote',
        'ingenio_id',
        'peso_acumulado',
        'despachado'
    ];

    protected $hidden = ['updated_at'];


    public static $rules = [
        'venta_id' => 'required',
    ];

    public $appends = [
        'humedad_kg',
        'merma',
        'peso_neto_seco',
        'neto_humedo',
        'peso_fino_zn',
        'peso_fino_ag',
        'peso_fino_sn',
        'peso_fino_pb',
        'peso_fino_sb',
        'peso_fino_au',
        'peso_fino_cu',
        'total',
        'total_venta',
        'habilitado_ingenio',
        'lote_destino_ingenio'
    ];

    public function getNetoHumedoAttribute()
    {
        return ($this->peso_bruto_humedo - $this->tara);
    }

    public function getHumedadKgAttribute()
    {
        return $this->peso_neto_humedo * ($this->humedad / 100);
    }

    public function getMermaAttribute()
    {
        return ($this->peso_neto_humedo - $this->humedad_kg) * $this->merma_porcentaje / 100;
    }

    public function getPesoNetoSecoAttribute()
    {
        return $this->peso_neto_humedo - $this->humedad_kg - $this->merma;
    }

    public function getPesoFinoZnAttribute()
    {
        return ($this->peso_neto_seco * $this->ley_zn) / 100;
    }

    public function getPesoFinoAgAttribute()
    {
        return ($this->peso_neto_seco * $this->ley_ag) / 10000;
    }

    public function getPesoFinoSnAttribute()
    {
        return ($this->peso_neto_seco * $this->ley_sn) / 100;
    }

    public function getPesoFinoPbAttribute()
    {
        return ($this->peso_neto_seco * $this->ley_pb) / 100;
    }

    public function getPesoFinoAuAttribute()
    {
        return ($this->peso_neto_seco * $this->ley_au) / 10000;
    }

    public function getPesoFinoCuAttribute()
    {
        return ($this->peso_neto_seco * $this->ley_cu) / 100;
    }

    public function getPesoFinoSbAttribute()
    {
        return ($this->peso_neto_seco * $this->ley_sb) / 100;
    }

    public function getTotalAttribute()
    {
        $total = $this->valor_neto_venta - $this->regalia_minera;
        if ($this->venta->tipo_lote == TipoLoteVenta::Ingenio)
            $total = $this->valor_neto_venta;
        return $total;
    }

    public function getTotalVentaAttribute()
    {
        $totales = Concentrado::whereVentaId($this->venta_id)->get()->sum('total');
        $anticipos = AnticipoVenta::whereVentaId($this->venta_id)->sum('monto');
        return ($totales - $anticipos);
    }

    public function getOrigenIngenioAttribute()
    {
        $c = Concentrado::find($this->ingenio_id);
        return $c->venta;
    }

    public function getHabilitadoIngenioAttribute()
    {
        $r = true;
        $concentrado = Concentrado::whereIngenioId($this->id)->first();
        if ($concentrado)
            $r = false;
        return $r;
    }

    public function getLoteDestinoIngenioAttribute()
    {
        $r = '';
        $concentrado = Concentrado::whereIngenioId($this->id)->first();
        if ($concentrado)
            $r = $concentrado->venta->lote;
        return $r;
    }

    public function getCalculoPesoAttribute()
    {
        $normal=FormularioLiquidacion::
        join('venta_formulario_liquidacion', 'formulario_liquidacion.id', '=', 'venta_formulario_liquidacion.formulario_liquidacion_id')
            ->where('venta_formulario_liquidacion.venta_id', $this->venta_id)
            ->where('formulario_liquidacion.fecha_liquidacion', '<=', $this->fecha)
            ->sum('peso_seco');

        $ingenio = Concentrado::whereVentaId($this->venta_id)
            ->where('fecha', '<=', $this->fecha)
            ->whereTipoLote(TipoLoteVenta::Ingenio)
            ->get()->sum('peso_neto_seco');
        return ($ingenio + $normal);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function ensayo()
    {
        return $this->morphOne(Ensayo::class, 'origen');
    }
}
