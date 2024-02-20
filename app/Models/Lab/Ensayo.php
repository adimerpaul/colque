<?php

namespace App\Models\Lab;
use App\Patrones\EstadoLaboratorio;
use Eloquent as Model;
class Ensayo extends Model
{
    public $table = 'laboratorio.ensayo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
       // 'codigo',
        'lote',
        'es_finalizado',
       // 'fecha_analisis',
        'fecha_finalizacion',
        'peso_humedo',
        'peso_seco',
        'peso_tara',
        'precio_unitario',
        'recepcion_id',
        'elemento_id',
        'origen_id',
        'origen_type',
        'resultado',
        'peso_muestra',
        'factor_volumetrico',
        'mililitros_gastados',
        'peso_oro',
        'peso_dore'

    ];
    public $appends = [
        'codigo_pedido', 'pesos', 'cliente', 'caracteristicas', 'resultado_elemento', 'mineral', 'fecha_recepcion'
    ];

    public function getResultadoElementoAttribute()
    {
        $resultado=sprintf("%s: %s%s", $this->elemento->simbolo , round($this->resultado,2) ,$this->elemento->unidad);
        return $resultado;
    }

    public function getCodigoPedidoAttribute()
    {
        return $this->recepcion->codigo_pedido;
    }

    public function getClienteAttribute()
    {
        return $this->recepcion->cliente->nombre;
    }

    public function getCaracteristicasAttribute()
    {
        return $this->recepcion->caracteristicas;
    }

    public function getMineralAttribute()
    {
        return $this->elemento->simbolo;
    }

    public function getFechaRecepcionAttribute()
    {
        return date('d/m/Y H:i', strtotime($this->created_at));
    }

    public function getTiempoAttribute()
    {
        $tiempo ="0";
        if($this->recepcion->estado==EstadoLaboratorio::Finalizado){
            $date1 = new \DateTime($this->recepcion->fecha_aceptacion);
            $date2 = new \DateTime($this->fecha_finalizacion);
            $diff = $date1->diff($date2);
            $tiempo = sprintf("%s:%s:%s", $diff->d*24 + $diff->h, $diff->i, $diff->s);
        }
        return $tiempo;
    }

    public function getPesosAttribute(){
        if($this->elemento_id==1)
            return sprintf("FV:%s, ML:%s, PM:%s", $this->factor_volumetrico, $this->mililitros_gastados, $this->peso_muestra);
        elseif ($this->elemento_id==2)
            return sprintf("PT:%s, PH:%s, PS:%s", $this->peso_tara, $this->peso_humedo, $this->peso_seco);
        else
            return sprintf("PO:%s, PD:%s, PM:%s", $this->peso_oro, $this->peso_dore, $this->peso_muestra);
    }

    public function elemento()
    {
        return $this->belongsTo(Elemento::class);
    }
    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class);
    }
    public function origen()
    {
        return $this->morphTo();
    }
}
