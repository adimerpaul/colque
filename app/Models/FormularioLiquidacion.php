<?php

namespace App\Models;

use App\Http\Controllers\ApiDescuentoBonificacionController;
use App\Http\Controllers\BonoController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CostoController;
use App\Http\Controllers\CotizacionDiariaController;
use App\Http\Controllers\CuentaCobrarController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\LiquidacionMineralController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoMineralController;
use App\Http\Controllers\ValorPorToneladaController;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\Estado;
use App\Patrones\TipoMaterial;
use App\Patrones\TipoPago;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioLiquidacion extends Model
{
    use HasFactory;

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
        'peso_seco',
        'estado',
        'observacion',
        'numero_tornaguia',
        'url_documento',
        'aporte_fundacion',
        'sacos',
        'saldo_favor',
        'tipo_cambio_id',
        'cliente_id',
        'chofer_id',
        'vehiculo_id',
        'neto_venta',
        'es_cancelado',
        'fecha_cancelacion',
        'tipo',
        'total_anticipo',
        'total_bonificacion',
        'total_retencion_descuento',
        'liquido_pagable',
        'prueba_saldo_favor',
        'prueba_neto_venta',
        'prueba_peso_seco',
        'motivo_anulacion',
        'presentacion',
        'fecha_hora_liquidacion',
        'boletas',
        'peso_de_balanza',
        'humedad_promedio',
        'humedad_kilo',
        'total_cuenta_cobrar',
        'ley_sn',
        'puntos',
        'en_molienda',
        'ubicacion',
        'comision_externa',
        'con_cotizacion_promedio',
        'tipo_material',
        'es_retirado',
        'es_cotizacion_manual',
        'cotizacion_manual',
        'ip_tablet',
        'con_ley_minima',
        'dispositivo',
        'recepcionado_laboratorio',
        'total_bonificacion_acumulativa',
        'con_tornaguia'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
//        'numero_lote' => 'required|string|max:10',
//        'fecha_cotizacion' => 'required',
//        'fecha_liquidacion' => 'nullable',
//        'fecha_pesaje' => 'nullable',
//        'producto' => 'required|string|max:255',


//        'peso_bruto' => 'required|numeric|min:0|max:999999',
//        'tara' => 'required|numeric|min:0|max:999999',
//        'anio' => 'nullable|numeric',
//        'valor_por_tonelada' => 'nullable|numeric',
//        'observacion' => 'nullable|string|max:255',
//        'numero_tornaguia' => 'nullable|string|max:255',
//        'cliente_id' => 'required|integer',
//        'chofer_id' => 'required|integer',
//        'vehiculo_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public $appends = [
        'lote',
        'es_escritura',
        'only_read',
        'peso_neto',
        'humedad_kg',
        'merma_kg',
        'peso_neto_seco',
        'laboratorio_promedio',
        'minerales_regalia',
        'valor_neto_venta',
        'humedad',
        'totales',
        'calculo_aporte',
        'operacion_cuadrar',
        'cantidad_devoluciones',
        'documento_que_falta',
        'valor_restado'
//        'ley_producto'
    ];

    public function setFechaPesajeAttribute($value)
    {
        $this->attributes['fecha_pesaje'] = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    }

    public function getLoteAttribute()
    {
        return sprintf("%s%d%s/%s", $this->sigla, $this->numero_lote, $this->letra, substr($this->anio, 2, 2));
    }

    public function getLoteSinGestionAttribute()
    {
        return sprintf("%s%d%s", $this->sigla, $this->numero_lote, $this->letra);
    }
    public function getSaldoPagoAttribute()
    {
        $obj = new MovimientoController();
        $saldo = $this->saldo_favor - $obj->getSumaMontos($this->id);
        return $saldo;
    }

    public function getPuntosCalculoAttribute()
    {
        $punto = 0;
        if($this->letra=='D' and $this->ley_estanio !=0 and !is_null($this->ley_estanio)){
            if($this->ley_estanio>70.0)
                return 35/50*$this->peso_neto_seco;

            $punto = Punto::whereLey(round($this->ley_estanio, 1, PHP_ROUND_HALF_DOWN))->first();
            if(empty($punto))
                $punto=0;
            else
                $punto = $punto->valor/50*$this->peso_neto_seco;
        }
        return $punto;
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

    public function getLaboratorioPromedioAttribute()
    {
        $obj = new LaboratorioController();
        if($this->created_at >'2024-01-14 00:00:00')
            return $obj->getPromedios2($this->id);
        else
            return $obj->getPromedios($this->id);
    }

    public function getCotizacionAgAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 1, $this->letra);
    }

    public function getCotizacionPbAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 2, $this->letra);
    }

    public function getCotizacionZnAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 3, $this->letra);
    }

    public function getCotizacionSnAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 4, $this->letra);
    }

    public function getCotizacionCuAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 5, $this->letra);
    }

    public function getCotizacionAuAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 7, $this->letra);
    }

    public function getCotizacionSbAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getCotizacion($this->fecha_cotizacion, 6, $this->letra);
    }

    public function getCodigoCajaAttribute()
    {
        $obj = new CajaController();
        $codigo = '';
        if ($this->es_cancelado)
            $codigo = $obj->getCodigoCaja($this->id, FormularioLiquidacion::class);
        return $codigo;
    }

//    public function getEsRetiradoAttribute()
//    {
//        $es=false;
//        $dev = Bono::whereFormularioLiquidacionId($this->id)->first();
//
//        if ($dev and $this->estado==Estado::Anulado){
//            $es=true;
//        }
//
//        return $es;
//    }

    public function getCostoAttribute()
    {
        $obj = new CostoController();
        return $obj->getCosto($this->id);
    }

    public function getHumedadAttribute()
    {
        $laboratorios = collect($this->laboratorioPromedio);
        $humedad = $laboratorios->where('mineral', 'Humedad')->first();
        if (is_null($humedad))
            return null;
        return $humedad->promedio;
    }

    public function getHumedadKgAttribute()
    {
        $humedadPromedio = $this->humedad;
        return $this->pesoNeto * ($humedadPromedio / 100);
    }

    public function getMermaKgAttribute()
    {
        return ($this->pesoNeto - $this->humedadKg) * ($this->merma / 100);
    }

    public function getPesoNetoSecoAttribute()
    {
        return ($this->pesoNeto - $this->humedadKg - $this->mermaKg);
    }

    public function getMineralesRegaliaAttribute()
    {
        $obj = new LiquidacionMineralController();
        $minerales = $this->minerales;

        $laboratorios = collect($this->laboratorioPromedio);
        $regalias = [];
        foreach ($minerales as $row) {
            $lab = $laboratorios->where('simbolo', $row->simbolo)->first();
            $ley = is_null($lab) ? 0 : ($lab->promedio);

            switch ($row->simbolo) {
                case 'Ag':
                    $valorBrutoVenta =
                        (
                            (
                                ((float)$this->pesoNetoSeco / 1000) *
                                (float)($ley * 100)
                            ) / 31.1035
                        ) *
                        (float)$row->monto * //cotizacion oficial
                        (float)$this->tipoCambio->dolar_venta;
                    break;
                case 'Au':
                    $valorBrutoVenta =
                        (
                            (
                                ((float)$this->pesoNetoSeco / 1000) *
                                (float)($ley)
                            ) / 31.1035
                        ) *
                        (float)$row->monto * //cotizacion oficial
                        (float)$this->tipoCambio->dolar_venta;
                    break;
                case 'Sb':
                    $valorBrutoVenta =
                        (float)$this->pesoNetoSeco *
                        (float)($ley / 100) *
                        (float)$row->monto / 1000 * //cotizacion oficial
                        (float)$this->tipoCambio->dolar_venta;
                    break;

                default:
                    $valorBrutoVenta =
                        (float)$this->pesoNetoSeco *
                        (float)($ley / 100) *
                        (float)2.2046223 *
                        (float)$row->monto * //cotizacion oficial
                        (float)$this->tipoCambio->dolar_venta;
            }

            $regalias[] = [
                'simbolo' => $row->simbolo,
                'unidad' => $row->unidad,
                'ley' => $ley,
                //todo cambiar para cada elemento, plata =
                'peso_fino' => ($row->simbolo == 'Ag' or  $row->simbolo == 'Au')? (($ley / 10000) * ($this->pesoNetoSeco)) : (($ley / 100) * ($this->pesoNetoSeco)),
                'cotizacion_oficial' => $row->monto,
                'valor_bruto_venta' => $valorBrutoVenta,
                'alicuota_interna' => $row->alicuota_interna,
                'alicuota_externa' => $row->alicuota_exportacion,
                'sub_total' =>  (($this->id>=3463 and $this->id<=3466) OR $this->id==3531 OR $this->id==4742 OR $this->id==5165 )? 0.00 : $obj->calculoRegalia($ley, $row->ley_minima, $this->con_ley_minima, $valorBrutoVenta, $row->alicuota_interna, $this->id),
                //solucion momentanea para lotes de ingenio

                //$ley >= $row->ley_minima  ? ($valorBrutoVenta * ($row->alicuota_interna / 100)) : 0,
            ];
        }
        return $regalias;
    }

    public function getMineralesRegaliaSinarcomAttribute()
    {
        $obj = new LiquidacionMineralController();
        $minerales = $this->minerales;

        $laboratorios = collect($this->laboratorioPromedio);
        $regalias = [];
        foreach ($minerales as $row) {
            $lab = $laboratorios->where('simbolo', $row->simbolo)->first();
            $ley = is_null($lab) ? 0 : ($lab->promedio);

            switch ($row->simbolo) {
                case 'Ag':
                    $valorBrutoVenta =
                        (
                            (
                                ((float)$this->pesoNetoSeco / 1000) *
                                (float)($ley * 100)
                            ) / 31.1035
                        ) *
                        (float)$row->monto * //cotizacion oficial
                        (float)$this->tipoCambio->dolar_venta;
                    break;
                case 'Au':
                    $valorBrutoVenta =
                        (
                            (
                                ((float)$this->pesoNetoSeco / 1000) *
                                (float)($ley)
                            ) / 31.1035
                        ) *
                        (float)$row->monto * //cotizacion oficial
                        (float)$this->tipoCambio->dolar_venta;
                    break;
                case 'Sb':
                    $valorBrutoVenta =
                        (float)$this->pesoNetoSeco *
                        (float)($ley / 100) *
                        (float)$row->monto / 1000 * //cotizacion oficial
                        (float)$this->tipoCambio->dolar_venta;
                    break;

                default:
                    $valorBrutoVenta =
                        (float)$this->pesoNetoSeco *
                        (float)($ley / 100) *
                        (float)2.2046223 *
                        (float)$row->monto * //cotizacion oficial
                        (float)$this->tipoCambio->dolar_venta;
            }

            $regalias[] = [
                'ley' => $ley,
                'ley_minima' => $row->ley_minima,
                'sub_total_sinarcom' => (($this->id>=3463 and $this->id<=3466) OR $this->id==3531 OR $this->id==4742 OR $this->id==5165 )? 0.00 :
                    $obj->calculoRegaliaSinarcom($valorBrutoVenta, $this->con_ley_minima, $row->alicuota_interna, $this->id)
                //solucion momentanea para lotes de ingenio

                //$ley >= $row->ley_minima  ? ($valorBrutoVenta * ($row->alicuota_interna / 100)) : 0,
            ];
        }
        return $regalias;
    }

    public function getEsQuemadoAttribute()
    {
        $es=false;

        $total = 0;
        foreach($this->minerales_regalia_sinarcom as $mineral){
            if($mineral[ "ley"]>=$mineral[ "ley_minima"])
                $total = $total + $mineral[ "sub_total_sinarcom"];
        }
        if($total == 0)
            $es=true;
        return $es;
    }

    public function getElementoAttribute()
    {
        switch ($this->letra) {
            case 'A':
                $elemento = 'Zn - Ag';
                break;
            case 'B':
                $elemento = 'Pb - Ag';
                break;
            case 'C':
                $elemento = 'Zn - Pb - Ag';
                break;
            case 'D':
                $elemento = 'Sn';
                break;
            case 'E':
                $elemento = 'Ag';
                break;
            case 'F':
                $elemento = 'Sb';
                break;
            case 'G':
                $elemento = 'Cu';
                break;
        }
        return $elemento;
    }
    public function getOperacionCuadrarAttribute()
    {
        $fecha= date('Y-m-d');
        $date1 = new \DateTime($this->fecha_pesaje);
        $date2 = new \DateTime($fecha);
        $diff = $date1->diff($date2);

        if($diff->days<15)
            $resultado = $this->totales['total_anticipos'] - $this->totales['total_devoluciones_internas'];
        else
            $resultado = $this->costo->laboratorio + $this->costo->dirimicion + $this->totales['total_anticipos'] - $this->totales['total_devoluciones_internas'];
        return $resultado;
    }

    public function getCantidadDevolucionesAttribute()
    {
        $obj = new BonoController();
        return $obj->getCantidadDevoluciones($this->id);
    }



    public function getTotalCuentasCobrarAttribute()
    {
        $obj = new CuentaCobrarController();
        return $obj->getTotalFormulario($this->id, ClaseCuentaCobrar::SaldoNegativo);
    }

    public function getTotalPrestamosAttribute()
    {
        $obj = new CuentaCobrarController();
        return $obj->getTotalFormulario($this->id, ClaseCuentaCobrar::Prestamo);
    }

    public function getMensajeCuentasCobrarAttribute()
    {
        $obj = new CuentaCobrarController();
        return $obj->getMensajeFormulario($this->id);
    }

    public function getMensajePrestamoAttribute()
    {
        $obj = new PrestamoController();
        return $obj->getMensajeFormulario($this->id);
    }

    public function getMineralesAttribute()
    {
        $obj = new LiquidacionMineralController();
        //return $obj->getMinerales($this->id, $this->fecha_cotizacion);
        return $obj->getMinerales($this->id, $this->fecha_liquidacion);
    }

//    public function getPkProductoAttribute()
//    {
//        $obj = new ProductoMineralController();
//        return $obj->getIdByLetra($this->letra);
//    }

    public function getValorNetoVentaAttribute()
    {
        if($this->cliente->cooperativa_id==44 and $this->tipo_material==TipoMaterial::Concentrado AND $this->letra=='E'){
            if($this->created_at<'2023-02-07 00:00:00')
                return ($this->pesoNetoSeco / 1000) * $this->valor_por_tonelada * 6.96;
            else{
                $obj = new ValorPorToneladaController();
                $porcentaje = $obj->getPagablePlata($this->ley_ag, $this->fecha_liquidacion);
                if($this->ley_ag<20 and $this->fecha_liquidacion<='2023-03-20')
                    return $this->valor_por_tonelada  * 6.96;
                else
                    return $this->valor_por_tonelada * $porcentaje/100 * 6.96;
            }
        }

        else
            return ($this->pesoNetoSeco / 1000) * $this->valor_por_tonelada * $this->tipoCambio->dolar_compra;
    }


    public function getCalculoAporteAttribute()
    {
        $decimales = round($this->totales['total_final'], 2);

        $modulo = $decimales - floor($decimales);
        if ($modulo == 0) {
            return 0;
        }

        $str = substr($decimales, strpos($decimales, '.') + strlen('.'));
        $decimales = (str_pad($str, 2, "0", STR_PAD_RIGHT));

     //   if ($decimales <= 50)
            $aporte = $decimales;
//        else
//            $aporte = $decimales;

        return $aporte;
    }

    public function getTotalesAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getTotales($this);
    }

    public function getBonificacionesDescuentosAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getDescuentosBonificaciones($this);
    }

    public function getRetencionesCooperativaAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getRetencionesCooperativa($this->cliente->cooperativa_id, $this->id);
    }

    public function getDescuentosCooperativaAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getDescuentosCooperativa($this->cliente->cooperativa_id, $this->id);
    }

    public function getBonificacionesCooperativaAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getBonificacionesCooperativa($this->cliente->cooperativa_id, $this->id);
    }

    public function getBonificacionesDescuentosBobAttribute()
    {
        $obj = new ApiDescuentoBonificacionController();
        return $obj->getDescuentosBonificacionesBob($this);
    }

    public function getUsuarioLiquidacionAttribute()
    {
        $obj = new HistorialController();
        return $obj->getUsuarioLiquidacion($this->id);
    }
    public function getUsuarioLiquidacionAndroidAttribute()
    {
        $obj = new HistorialController();
        return $obj->getUsuarioLiquidacionAndroid($this->id);
    }

    public function getUsuarioCancelacionAttribute()
    {
        $obj = new HistorialController();
        return $obj->getUsuarioCancelacion($this->id);
    }

    public function getValorRestadoAttribute(){
        $desc = FormularioDescuento::whereFormularioLiquidacionId($this->id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereNombre('BONO CALIDAD');
            })
            ->count();

        $restado = false;
        if($desc>0)
            $restado=true;

        return $restado;
    }

    public function getCostoPesajeAttribute()
    {
        return ($this->costo->pesaje * $this->boletas);
    }

    public function getCostoPublicidadAttribute()
    {
        return (($this->peso_seco / 1000) * $this->tipoCambio->dolar_compra * $this->costo->publicidad);
    }

    public function getCostoProProductorAttribute()
    {
        return (($this->peso_seco / 1000) * $this->tipoCambio->dolar_compra * $this->costo->pro_productor);
    }

    public function getDocumentoQueFaltaAttribute()
    {
        $lista = '';
        $documentos = DocumentoCompra::whereFormularioLiquidacionId($this->id)->where('descripcion', '<>', \App\Patrones\DocumentoCompra::Anticipos)->where('agregado', false)->get();
        foreach ($documentos as $documento) {
            if ($lista === '')
                $lista = $documento->descripcion;
            else
                $lista = $lista . ', ' . $documento->descripcion;
        }
        return $lista;
    }

    public function getFaltanDocumentosAttribute()
    {
        $falta = false;
        $documentos = DocumentoCompra::whereFormularioLiquidacionId($this->id)
                ->where('descripcion', '<>', \App\Patrones\DocumentoCompra::LaboratorioCliente)->where('descripcion', '<>', \App\Patrones\DocumentoCompra::Anticipos)->where('agregado', false)->count();
        if ($documentos > 0) {
            $falta = true;
        }
        return $falta;
    }

    public function getLeyEstanioAttribute(){
        $ley='';
        foreach($this->laboratorio_promedio as $lab){
            if($lab->simbolo=='Sn'){
                $ley=number_format($lab->promedio,2);
            }
        }
        return $ley;
    }


    public function getCodigosAnticiposAttribute()
    {
        $anticipo= Anticipo::whereFormularioLiquidacionId($this->id)->get();
        $obj = new CajaController();
        $codigo = '';
        foreach($anticipo as $a){
            if($a->es_cancelado)
                $codigo=$codigo.', '.$obj->getCodigoCaja($a->id, Anticipo::class);
        }

        return substr($codigo, 2);
    }

    public function getCostoComisionAttribute(){
        $costo=0;
            if($this->comision_externa){
                if($this->letra=='A' OR $this->letra=='B')
                    $costo = ($this->peso_seco / 1000) * 10;
                else if($this->letra=='C' OR $this->letra=='E')
                    $costo = ($this->peso_seco / 1000) * 5;
            }
        return $costo;

    }

    public function getLoteVentaAttribute()
    {
        $lote='';
        $venta= VentaFormularioLiquidacion::whereFormularioLiquidacionId($this->id)->first();
        if(!empty($venta))
            $lote=$venta->venta->lote;
        return $lote;
    }
//    public function getLeyZnAttribute(){
//        $ley='';
//        foreach($this->laboratorio_promedio as $lab){
//            if($lab->simbolo=='Zn'){
//                $ley=number_format($lab->promedio,2);
//            }
//        }
//        return $ley;
//    }
//
    public function getLeyAgAttribute(){
        $ley='';
        foreach($this->laboratorio_promedio as $lab){
            if($lab->simbolo=='Ag'){
                $ley=$lab->promedio;
            }
        }
        return $ley;
    }
//    public function getLeyPbAttribute(){
//        $ley='';
//        foreach($this->laboratorio_promedio as $lab){
//            if($lab->simbolo=='Pb'){
//                $ley=number_format($lab->promedio,2);
//            }
//        }
//        return $ley;
//    }
//
//    public function getLeySbAttribute(){
//        $ley='';
//        foreach($this->laboratorio_promedio as $lab){
//            if($lab->simbolo=='Sb'){
//                $ley=number_format($lab->promedio,2);
//            }
//        }
//        return $ley;
//    }
//
//    public function getLeyAuAttribute(){
//        $ley='';
//        foreach($this->laboratorio_promedio as $lab){
//            if($lab->simbolo=='Au'){
//                $ley=number_format($lab->promedio,2);
//            }
//        }
//        return $ley;
//    }
//

    public function getCotizacionesPromedioAgAttribute(){
        if($this->letra!='E')
            return 0;

        if($this->con_cotizacion_promedio==false){
            $cotizacion = CotizacionDiaria::where('fecha', $this->fecha_cotizacion)->whereMineralId(1)->first();
            return $cotizacion->monto;
        }

        if($this->con_cotizacion_promedio==true and $this->es_cotizacion_manual==true){
            return $this->cotizacion_manual;
        }
        $c='';
        $recepcion = date( 'Y-m-d', strtotime($this->created_at));
        $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('3')->get();//->sum('monto');


        if($cotizaciones->count()<3)
            return '';

        $dia = date( 'D', strtotime($recepcion));
        if($dia =='Sat'){
            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 2 days'));
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('3')->get();//->sum('monto');
        }
        elseif($dia =='Fri'){
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->first();
            $cotSabado=round($cotizaciones->monto, 2) .' '. $cotizaciones->unidad_form;
            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 3 days'));
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('2')->get();
            $c=$cotSabado;

            foreach($cotizaciones as $cotizacion){
                $c = sprintf("%s \n %s %s", $c, round($cotizacion->monto, 2), $cotizacion->unidad_form);
            }
            return $c;
        }

        elseif($dia =='Thu'){
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('2')->get();

            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 4 days'));
            $cotMartes = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->first();
            if(is_null($cotMartes))
                return '';
            $cotMartes= round($cotMartes->monto, 2).' '. $cotMartes->unidad_form;

            $c=$cotMartes;

            foreach($cotizaciones as $cotizacion){
                $c = sprintf("%s \n %s %s", $c, round($cotizacion->monto, 2), $cotizacion->unidad_form);
            }
            return $c;
        }

        foreach($cotizaciones as $cotizacion){
            $c = sprintf("%s \n %s %s", $c, round($cotizacion->monto, 2), $cotizacion->unidad_form);
        }
        return $c;
    }

    public function getCotizacionPromedioAgAttribute(){
        if($this->letra!='E')
            return 0;

        if($this->con_cotizacion_promedio==false){
            $cotizacion = CotizacionDiaria::where('fecha', $this->fecha_cotizacion)->whereMineralId(1)->first();
            return $cotizacion->monto;
        }

        if($this->con_cotizacion_promedio==true and $this->es_cotizacion_manual==true){
            return $this->cotizacion_manual;
        }

        $recepcion = date( 'Y-m-d', strtotime($this->created_at));
        $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('3')->get();//->sum('monto');

        if($recepcion<'2023-02-07' and $this->cliente->cooperativa_id==44)
            return 23.62;

        if($cotizaciones->count()<3)
            return 0.00;

        $dia = date( 'D', strtotime($recepcion));
        if($dia =='Sat'){
            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 2 days'));
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('3')->get();//->sum('monto');
        }
        elseif($dia =='Fri'){
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->first();
            $cotSabado=$cotizaciones->monto;
            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 3 days'));
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('2')->get();
            $cotizaciones = ($cotizaciones->sum('monto') + $cotSabado)/3;
            return $cotizaciones;
        }

        elseif($dia =='Thu'){
            $cotizaciones = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->limit('2')->get();

            $recepcion = date('Y-m-d', strtotime($recepcion . ' + 4 days'));
            $cotMartes = CotizacionDiaria::where('fecha', '>', $recepcion)->whereMineralId(1)->orderBy('fecha')->first();
            if(is_null($cotMartes))
                return 0;
            $cotMartes=$cotMartes->monto;
            $cotizaciones = ($cotizaciones->sum('monto') + $cotMartes)/3;
            return $cotizaciones;
        }

        return $cotizaciones->avg('monto');
    }


    public function getLeyProductoAttribute(){
        $ley='';
        foreach($this->laboratorio_promedio as $lab){
            if($lab->simbolo=='Zn'){
                $ley=$ley. ', Zinc: ' . number_format($lab->promedio,2).  ' '.$lab->unidad;
            }
            elseif($lab->simbolo=='Pb'){
                $ley=$ley. ', Plomo: ' . number_format($lab->promedio,2).  ' '.$lab->unidad;
            }
            elseif($lab->simbolo=='Ag'){
                $ley=$ley. ', Plata: ' . number_format($lab->promedio,2).  ' '.$lab->unidad;
            }
            elseif($lab->simbolo=='Sn'){
                $ley=$ley. ', Estaño: ' . number_format($lab->promedio,2).  ' '.$lab->unidad;
            }
            elseif($lab->simbolo=='Sb'){
                $ley=$ley. ', Antimonio: ' . number_format($lab->promedio,2).  ' '.$lab->unidad;
            }
            elseif($lab->simbolo=='Au'){
                $ley=$ley. ', Oro: ' . number_format($lab->promedio,2).  ' '.$lab->unidad;
            }
            elseif($lab->simbolo=='Cu'){
                $ley=$ley. ', Cobre: ' . number_format($lab->promedio,2).  ' '.$lab->unidad;
            }
        }
        return substr($ley, 2);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoCambio()
    {
        return $this->belongsTo(\App\Models\TipoCambio::class, 'tipo_cambio_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id');
    }

    /**
     * @return \Illuminate\Database\Elcoquent\Relations\BelongsTo
     **/
    public function chofer()
    {
        return $this->belongsTo(\App\Models\Chofer::class, 'chofer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function vehiculo()
    {
        return $this->belongsTo(\App\Models\Vehiculo::class, 'vehiculo_id');
    }

//    public function producto()
//    {
//        return $this->belongsTo(Producto::class);
//    }

    public function descuentoBonificaciones()
    {
        return $this->belongsToMany(DescuentoBonificacion::class, 'formulario_descuento', 'formulario_liquidacion_id', 'descuento_bonificacion_id');
    }

    public function liquidacioMinerales()
    {
        return $this->hasMany(LiquidacionMineral::class);
    }

    public function laboratorios()
    {
        return $this->hasMany(Laboratorio::class);
    }

    public function anticipos()
    {
        return $this->hasMany(Anticipo::class);
    }

    public function bonos()
    {
        return $this->hasMany(Bono::class);
    }

    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }

    public function cuenta()
    {
        return $this->morphOne(CuentaCobrar::class, 'origen');
    }
    public function ensayo()
    {
        return $this->morphOne(Ensayo::class, 'origen');
    }
}
