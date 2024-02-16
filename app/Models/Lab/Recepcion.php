<?php

namespace App\Models\Lab;
use App\Patrones\EstadoLaboratorio;
use Eloquent as Model;

class Recepcion extends Model
{
    public $table = 'laboratorio.recepcion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'caracteristicas',
        'numero',
        'mes',
        'anio',
        'codigo',
        'es_cancelado',
        'alta',
        'cliente_id',
        'fecha_aceptacion',
        'fecha_finalizacion',
        'fecha_rechazo',
        'estado',
        'a_caja',
        'anticipo',
        'es_finalizado'
    ];

    public $appends = [
        'codigo_pedido', 'monto_pagado'
    ];

    public function getCodigoPedidoAttribute()
    {
        return sprintf("CL%s-%s%s", $this->numero, $this->mes, $this->anio);
    }
    public function getCodigoPedidoCortoAttribute()
    {
        return sprintf("CL%s", $this->numero);
    }
    public function getDescripcionAttribute()
    {
        //depende glosa
        $ensayos=Ensayo::whereRecepcionId($this->id)->groupBy('elemento_id')->select('elemento_id')->get();
        $elementos = "Muestra de ";
        foreach ($ensayos as $ensayo) {
            if ($ensayo->elemento_id==1)
                $elementos = $elementos . "Sn, ";
            if ($ensayo->elemento_id == 2)
                $elementos = $elementos . "Humedad, ";
            if ($ensayo->elemento_id == 3)
                $elementos = $elementos . "Plata, ";
        }
        $elementos = substr(trim($elementos), 0, -1);

        return $elementos;

    }

    public function getLotesAttribute()
    {
        $ensayos=Ensayo::whereRecepcionId($this->id)->select('lote')->orderBy('lote')->groupBy('lote')->get();
        $lotes = "";
        foreach ($ensayos as $ensayo) {
                $lotes =$lotes. ", ". $ensayo->lote ;
        }
        $lotes = substr(trim($lotes), 2);

        return $lotes;

    }

    public function getEnsayosSinFinalizarAttribute(){
        $ensayosHumedad = Ensayo::whereRecepcionId($this->id)->wherePesoSeco(0.00)->whereElementoId(2)->count();

        $ensayosEstanio = Ensayo::whereRecepcionId($this->id)->whereMililitrosGastados(0.00)->whereElementoId(1)->count();

        $ensayosPlata = Ensayo::whereRecepcionId($this->id)->wherePesoOro(0.00)->whereElementoId(3)->count();
        return $ensayosHumedad + $ensayosEstanio + $ensayosPlata;
    }

    public function getGlosaAttribute()
    {
        //depende glosa
        $ensayos=Ensayo::whereRecepcionId($this->id)->groupBy('elemento_id')->select('elemento_id')->get();
        $elementos = "ANÁLISIS DE LABORATORIO DE ";
        foreach ($ensayos as $ensayo) {
            if ($ensayo->elemento_id==1)
                $elementos = $elementos . "SN, ";
            if ($ensayo->elemento_id == 2)
                $elementos = $elementos . "HUMEDAD, ";
            if ($ensayo->elemento_id == 3)
                $elementos = $elementos . "PLATA, ";
        }
        $elementos = substr(trim($elementos), 0, -1);

        return $elementos;

    }

    public function getCantidadAttribute()
    {
        return Ensayo::whereRecepcionId($this->id)->count();
    }

    public function getPrecioTotalAttribute()
    {
        return Ensayo::whereRecepcionId($this->id)->sum('precio_unitario');
    }

    public function getEsCanceladoAttribute()
    {
        $esCancelado=false;
        $total= Ensayo::whereRecepcionId($this->id)->sum('precio_unitario');
        $pagados=PagoMovimiento::whereOrigenId($this->id)->whereOrigenType(Recepcion::class)->whereEsCancelado(true)->whereAlta(true)->sum('monto');
        if($total==$pagados)
            $esCancelado=true;

        return $esCancelado;
    }

    public function getSaldoAttribute()
    {
        $total= Ensayo::whereRecepcionId($this->id)->sum('precio_unitario');
        $pagados=PagoMovimiento::whereOrigenId($this->id)->whereOrigenType(Recepcion::class)->whereAlta(true)->sum('monto');
        $saldo=$total - $pagados;

        return $saldo;
    }

    public function getCantidadEstanioAttribute()
    {
        return Ensayo::whereRecepcionId($this->id)->whereElementoId(1)->count();
    }

    public function getCantidadHumedadAttribute()
    {
        return Ensayo::whereRecepcionId($this->id)->whereElementoId(2)->count();
    }

    public function getCantidadPlataAttribute()
    {
        return Ensayo::whereRecepcionId($this->id)->whereElementoId(3)->count();
    }

    public function getMontoHumedadAttribute()
    {
        return ($this->cantidad_humedad *10);
    }

    public function getMontoEstanioAttribute()
    {
        return ($this->cantidad_estanio *40);
    }

    public function getMontoPlataAttribute()
    {
        return ($this->cantidad_plata *40);
    }

    public function getMontoPagadoAttribute()
    {
        $monto=0;
        $pagoMovimiento=PagoMovimiento::whereOrigenId($this->id)->whereAlta(true)->whereOrigenType(Recepcion::class)->orderBy('id')->first();
        if($pagoMovimiento)
            $monto=$pagoMovimiento->monto;
        return $monto;
    }

    public function getFechaAnalisisAttribute(){
        $fecha='';
        $ensayos = Ensayo::whereRecepcionId($this->id)->orderBy('fecha_analisis')->get();
        if($ensayos)
            $fecha = $ensayos[0]->fecha_analisis;
        return $fecha;
    }

    public function getOnlyReadAttribute()
    {
        return $this->estado === EstadoLaboratorio::Recepcionado ? "" : "pointer-events: none;";
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }
}
