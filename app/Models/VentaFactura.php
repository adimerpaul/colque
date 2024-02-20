<?php

namespace App\Models;


use App\Http\Controllers\LiquidacionMineralController;
use App\Http\Controllers\VentaController;
use App\Patrones\TipoFactura;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VentaFactura extends Model
{
    use HasFactory;

    public $table = 'venta';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'sigla',
        'numero_lote',
        'letra',
        'anio',
        'producto',
        'comprador_id',
        'tipo',
        'monto_total',
        'tipo_factura',
        'fecha_emision',
        'puerto_destino',
        'puerto_transito',
        'incoterm',
        'tipo_cambio',
        'documento_sector_id',
        'moneda_id',
        'pais_id',
        'unidad_id',
        'neto_humedo',
        'humedad',
        'merma'
    ];

    protected $hidden = ['created_at', 'updated_at', 'a_operaciones',
        'es_cancelado',
        'url_documento',
        'transporte',
        'otros_costos',
        'lote_comprador',
        'tipo_transporte',
        'trayecto',
        'tranca',
        'codigo_odd',
        'liquidado',
        'estado',
        'tipo_lote',
        'es_despachado',
        'fecha_promedio',
        'fecha_entrega',
        'empaque',
        'fecha_venta',
        'fecha_cobro',
        'peso_neto_seco',
        'valor_neto_venta',
        'utilidad',
        'margen',
        'tipo',
        'es_aprobado',
        'moneda_id',
        'documento_sector_id',
        'pais_id',


    ];

    protected $dates = ['deleted_at'];



    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lote_comprador'=>'required',
        'tipo_transporte'=>'required',
        'trayecto'=>'required',
        'tranca'=>'required',
        'municipio'=>'required',
        'comprador_id'=>'required',
    ];

    public $appends = [
        'lote',
        'nit_emisor',
        'razon_social_emisor',
        'municipio',
        'codigo_sucursal',
        'telefono',
        'direccion',
        'codigo_punto_venta',
        'concentrado_granel',
        'descuento_adicional',
        'usuario',
        'nombre_razon_social',
        'direccion_comprador',
        'codigo_tipo_documento_identidad',
        'nim',
        'numero_documento',
        'ruex',
        'origen',
        'codigo_cliente',
        'monto_total_sujeto_iva',
        'monto_gift_card',
        'pais_destino',
        'kilos_netos_humedos',
        'humedad_porcentaje',
        'humedad_valor',
        'merma_porcentaje',
        'merma_valor',
        'kilos_netos_secos',
        'gastos_realizacion',
        'minerales',
        'codigo_moneda',
        'codigo_documento_sector',
        'monto_total_moneda',
        'monto_fob'
    ];


    ///////////
    public function getNitEmisorAttribute(){
        return '370883022';
    }

    public function getRazonSocialEmisorAttribute(){
        return 'COLQUECHACA MINING LTDA.';
    }

    public function getMunicipioAttribute(){
        return 'ORURO';
    }

    public function getTelefonoAttribute(){
        return '67200160';
    }

    public function getCodigoSucursalAttribute(){
        return '0';
    }

    public function getDireccionAttribute(){
        return 'AVENIDA MARIA BARZOLA ENTRE CORIHUAYRA Y CELESTINO GUTIERREZ NRO. S/N ZONA/BARRIO: ESTE';
    }

    public function getCodigoPuntoVentaAttribute(){
        return '0';
    }

    public function getDescuentoAdicionalAttribute(){
        return 0;
    }

    public function getUsuarioAttribute(){
        return 'daoc';//auth()->user()->id;
    }

    public function getConcentradoGranelAttribute(){
        return 'CONCENTRADO DE MINERALES '. substr($this->producto, 4);
    }

    public function getNombreRazonSocialAttribute(){
        $nombre='';
        if($this->comprador AND ($this->tipo_factura==TipoFactura::CompraVenta OR $this->tipo_factura==TipoFactura::ExportacionMinera))
            $nombre = $this->comprador->razon_social;

        return $nombre;
    }

    public function getDireccionCompradorAttribute(){
        $nombre='';
        if($this->comprador AND ($this->tipo_factura==TipoFactura::CompraVenta OR $this->tipo_factura==TipoFactura::ExportacionMinera))
            $nombre = $this->comprador->direccion;

        return $nombre;
    }

    public function getCodigoTipoDocumentoIdentidadAttribute(){
        return '1';
    }

    public function getNumeroDocumentoAttribute(){
        $nit=0;
        if($this->comprador AND ($this->tipo_factura==TipoFactura::CompraVenta OR $this->tipo_factura==TipoFactura::ExportacionMinera))
            $nit = $this->comprador->nit;

        return $nit;
    }


    public function getRuexAttribute(){
        return '92920';
    }

    public function getNimAttribute(){
        $nit=0;
        if($this->comprador)
            $nit = $this->comprador->nro_nim;

        return $nit;
    }

    public function getOrigenAttribute(){
        return 'BOLIVIA';
    }

    public function getCodigoClienteAttribute(){
        $nit=0;
        if($this->comprador AND ($this->tipo_factura==TipoFactura::CompraVenta OR $this->tipo_factura==TipoFactura::ExportacionMinera))
            $nit = $this->comprador->nit;

        return $nit;
    }

    public function getMontoTotalSujetoIvaAttribute(){

        return round( $this->monto_total,2 );
    }

    public function getMontoGiftCardAttribute(){
        return 0;
    }

    public function getMontoTotalMonedaAttribute(){
        return round( $this->tipo_cambio * round($this->monto_total, 2), 2);
    }

    public function getMontoFobAttribute(){
        return round( ($this->monto_total_sujeto_iva- $this->gastos_realizacion), 2);
    }

    public function getCodigoMonedaAttribute(){
        $codigo='';
        $parametricas = ParametricaImpuestos::find($this->moneda_id);
        if($parametricas)
            $codigo = $parametricas->codigo;
        return $codigo;
    }

    public function getCodigoDocumentoSectorAttribute(){
        $codigo='';
        $parametricas = ParametricaImpuestos::find($this->documento_sector_id);
        if($parametricas)
            $codigo = $parametricas->codigo;
        return $codigo;
    }


    public function getPaisDestinoAttribute(){
        $codigo='';
        $parametricas = ParametricaImpuestos::find($this->pais_id);
        if($parametricas)
            $codigo = $parametricas->codigo;
        return $codigo;
    }

    public function getUnidadMedidaAttribute(){
        $codigo='';
        $parametricas = ParametricaImpuestos::find($this->unidad_id);
        if($parametricas)
            $codigo = $parametricas->codigo;
        return $codigo;
    }


    public function getKilosNetosHumedosAttribute(){
        return $this->neto_humedo;
    }




    public function getMermaPorcentajeAttribute(){
      return $this->merma;
    }

    public function getHumedadPorcentajeAttribute(){
        return $this->humedad;
    }


    public function getGastosRealizacionAttribute(){
        return round( ($this->monto_total * 0.45), 2);
    }

    public function getMineralesAttribute()
    {
        $obj = new LiquidacionMineralController();
        return $obj->getMineralesVenta($this->id, date("Y-m-d", strtotime($this->fecha_emision)), $this->tipo_factura);
    }

    public function getHumedadValorAttribute()
    {
        $humedad = $this->humedad;
        return $this->neto_humedo * ($humedad / 100);
    }

    public function getMermaValorAttribute()
    {
        return ($this->neto_humedo - $this->humedad_valor) * ($this->merma / 100);
    }

    public function getKilosNetosSecosAttribute()
    {
        return ($this->neto_humedo - $this->humedad_valor - $this->merma_valor);
    }

    //////

    public function getLoteAttribute()
    {
        return sprintf("%s%d%s/%s", $this->sigla, $this->numero_lote, $this->letra, substr($this->anio, 2, 2));
    }



    public function getSumaPesoNetoHumedoAttribute()
    {
        $obj = new VentaController();
        $formularios = $obj->getCompras($this->id);
        $pesoNetoHumedo = 0;

        foreach ($formularios as $formulario) {
            $pesoNetoHumedo = $pesoNetoHumedo + $formulario->peso_neto;
        }
        return $pesoNetoHumedo;
    }

    public function getSumaPesoBrutoHumedoAttribute()
    {
        $obj = new VentaController();
        $formularios = $obj->getCompras($this->id);
        $pesoBrutoHumedo = 0;

        foreach ($formularios as $formulario) {
            $pesoBrutoHumedo = $pesoBrutoHumedo + $formulario->peso_bruto;
        }
        return $pesoBrutoHumedo;
    }

    public function getSumaPesoNetoSecoAttribute()
    {
        $obj = new VentaController();
        $formularios = $obj->getCompras($this->id);
        $pesoSeco = $formularios->sum('peso_seco');

        return $pesoSeco;
    }

    public function getSumaValorNetoVentaAttribute()
    {
        $obj = new VentaController();
        $formularios = $obj->getCompras($this->id);
        $netoVenta = $formularios->sum('neto_venta');

        return $netoVenta;
    }



    public function getSumaPesoFinoSnAttribute()
    {
        $pesoFino = 0;
        if ($this->letra == 'D') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);
            foreach ($formularios as $formulario) {
                foreach ($formulario->laboratorio_promedio as $lab) {
                    if ($lab->simbolo == 'Sn') {
                        $pesoFino = $pesoFino + ($lab->promedio * $formulario->peso_seco / 100);
                    }
                }
            }
        }
        return $pesoFino;
    }

    public function getSumaCotizacionSnAttribute()
    {
        $cotizacionSn = 0;
        if ($this->letra == 'D') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);
            foreach ($formularios as $formulario) {
                $cotizacionSn = $cotizacionSn + ($formulario->cotizacion_sn * $formulario->peso_seco);
            }
        }
        return $cotizacionSn;
    }

    public function getSumaPesoFinoAgAttribute()
    {
        $pesoFino = 0;
        if ($this->letra == 'C' or $this->letra == 'A' or $this->letra =='B' or $this->letra =='E') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);
            foreach ($formularios as $formulario) {
                foreach ($formulario->laboratorio_promedio as $lab) {
                    if ($lab->simbolo == 'Ag') {
                        $pesoFino = $pesoFino + ($lab->promedio * $formulario->peso_seco / 100);
                    }
                }
            }
        }
        return $pesoFino;
    }

    public function getSumaCotizacionAgAttribute()
    {
        $cotizacionAg = 0;
        if ($this->letra == 'C' or $this->letra == 'A' or $this->letra =='B' or $this->letra =='E') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);

            foreach ($formularios as $formulario) {
                $cotizacionAg = $cotizacionAg + ($formulario->cotizacion_ag * $formulario->peso_seco);
            }
        }
        return $cotizacionAg;
    }

    public function getSumaPesoFinoZnAttribute()
    {
        $pesoFino = 0;
        if ($this->letra == 'C' or $this->letra == 'A') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);
            foreach ($formularios as $formulario) {
                foreach ($formulario->laboratorio_promedio as $lab) {
                    if ($lab->simbolo == 'Zn') {
                        $pesoFino = $pesoFino + ($lab->promedio * $formulario->peso_seco / 100);
                    }
                }
            }
        }
        return $pesoFino;
    }

    public function getSumaCotizacionZnAttribute()
    {
        $cotizacionZn = 0;
        if ($this->letra == 'C' or $this->letra == 'A') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);

            foreach ($formularios as $formulario) {
                $cotizacionZn = $cotizacionZn + ($formulario->cotizacion_zn * $formulario->peso_seco);
            }
        }
        return $cotizacionZn;
    }

    public function getSumaPesoFinoPbAttribute()
    {
        $pesoFino = 0;
        if ($this->letra == 'C' or $this->letra == 'B') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);
            foreach ($formularios as $formulario) {
                foreach ($formulario->laboratorio_promedio as $lab) {
                    if ($lab->simbolo == 'Pb') {
                        $pesoFino = $pesoFino + ($lab->promedio * $formulario->peso_seco / 100);
                    }
                }
            }
        }
        return $pesoFino;
    }

    public function getSumaCotizacionPbAttribute()
    {
        $cotizacionPb = 0;
        if ($this->letra == 'C' or $this->letra == 'B') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);

            foreach ($formularios as $formulario) {
                $cotizacionPb = $cotizacionPb + ($formulario->cotizacion_pb * $formulario->peso_seco);
            }
        }
        return $cotizacionPb;
    }

    public function getSumaPesoFinoSbAttribute()
    {
        $pesoFino = 0;
        if ($this->letra == 'F') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);
            foreach ($formularios as $formulario) {
                foreach ($formulario->laboratorio_promedio as $lab) {
                    if ($lab->simbolo == 'Sb') {
                        $pesoFino = $pesoFino + ($lab->promedio * $formulario->peso_seco / 100);
                    }
                }
            }
        }
        return $pesoFino;
    }

    public function getSumaCotizacionSbAttribute()
    {
        $cotizacionSb = 0;
        if ($this->letra == 'F') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);

            foreach ($formularios as $formulario) {
                $cotizacionSb = $cotizacionSb + ($formulario->cotizacion_sb * $formulario->peso_seco);
            }
        }
        return $cotizacionSb;
    }

    public function getSumaPesoFinoAuAttribute()
    {
        $pesoFino = 0;
        if ($this->letra == 'F') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);
            foreach ($formularios as $formulario) {
                foreach ($formulario->laboratorio_promedio as $lab) {
                    if ($lab->simbolo == 'Au') {
                        $pesoFino = $pesoFino + ($lab->promedio * $formulario->peso_seco / 100);
                    }
                }
            }
        }
        return $pesoFino;
    }

    public function getSumaCotizacionAuAttribute()
    {
        $cotizacionAu = 0;
        if ($this->letra == 'F') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);

            foreach ($formularios as $formulario) {
                $cotizacionAu = $cotizacionAu + ($formulario->cotizacion_au * $formulario->peso_seco);
            }
        }
        return $cotizacionAu;
    }

    public function getSumaPesoFinoCuAttribute()
    {
        $pesoFino = 0;
        if ($this->letra == 'G') {
            $obj = new VentaController();
            $formularios = $obj->getCompras($this->id);
            foreach ($formularios as $formulario) {
                foreach ($formulario->laboratorio_promedio as $lab) {
                    if ($lab->simbolo == 'Cu') {
                        $pesoFino = $pesoFino + ($lab->promedio * $formulario->peso_seco / 100);
                    }
                }
            }
        }
        return $pesoFino;
    }

    public function getHumedadComprasAttribute()
    {
        $humedad = 0;
        $obj = new VentaController();
        $formularios = $obj->getCompras($this->id);
        $sumaPNH = $formularios->sum('peso_neto');
        foreach ($formularios as $formulario) {
            foreach ($formulario->laboratorio_promedio as $lab) {
                if ($lab->simbolo == 'H2O') {
                    $humedad = $humedad + ($lab->promedio * $formulario->peso_neto / 100);
                }
            }
        }
        $humedad = ($humedad / $sumaPNH) * 100;

        return $humedad;
    }

    public function getSumaLeySnAttribute()
    {
        $leySn = 0;
        if ($this->letra == 'D') {
            $leySn = ($this->suma_peso_fino_sn / $this->suma_peso_neto_seco) * 100;
        }
        return $leySn;
    }

    public function getSumaLeyAgAttribute()
    {
        $leyAg = 0;
        if ($this->letra == 'A' or $this->letra == 'B' or $this->letra == 'C' or $this->letra =='E') {
            $leyAg = ($this->suma_peso_fino_ag / $this->suma_peso_neto_seco) * 10000;
        }
        return $leyAg;
    }

    public function getSumaLeyZnAttribute()
    {
        $leyZn = 0;
        if ($this->letra == 'C' or $this->letra == 'A') {
            $leyZn = ($this->suma_peso_fino_zn / $this->suma_peso_neto_seco) * 100;
        }
        return $leyZn;
    }

    public function getSumaLeyPbAttribute()
    {
        $leyPb = 0;
        if ($this->letra == 'C' or $this->letra == 'B') {
            $leyPb = ($this->suma_peso_fino_pb / $this->suma_peso_neto_seco) * 100;
        }
        return $leyPb;
    }

    public function getSumaLeySbAttribute()
    {
        $leySb = 0;
        if ($this->letra == 'F') {
            $leySb = ($this->suma_peso_fino_sb / $this->suma_peso_neto_seco) * 100;
        }
        return $leySb;
    }

    public function getSumaLeyAuAttribute()
    {
        $leyAu = 0;
        if ($this->letra == 'F') {
            $leyAu = ($this->suma_peso_fino_au / $this->suma_peso_neto_seco) * 10000;
        }
        return $leyAu;
    }

    public function getSumaLeyCuAttribute()
    {
        $leySb = 0;
        if ($this->letra == 'G') {
            $leySb = ($this->suma_peso_fino_cu / $this->suma_peso_neto_seco) * 100;
        }
        return $leySb;
    }








    public function getSumaAnticiposAttribute(){
        return PagoMovimiento::whereOrigenType(Venta::class)->whereOrigenId($this->id)->whereAlta(true)->sum('monto');
    }

    public function getCostosOtrosAttribute(){
        $costo = CostoVenta::whereVentaId($this->id)->sum('monto');
        if(is_null($costo))
            $costo = 0.00;
        return $costo;
    }

    public function getTotalAnticiposAttribute(){
        return AnticipoVenta::whereVentaId($this->id)->sum('monto');
    }

    public function getSaldoAttribute(){
        return ($this->monto - $this->suma_anticipos);
    }



    public function getPeriodoCobroAttribute(){

        if(!$this->es_cancelado)
            return 0;
        $fechaP = Carbon::parse($this->fecha_venta);
        $fechaC = Carbon::parse($this->fecha_cobro);

        $dias = $fechaC->diffInDays($fechaP);
        return $dias;
    }

    public function getFechaDespachoAttribute(){
        $fecha= '';
        $f = VentaFormularioLiquidacion::whereVentaId($this->id)->whereDespachado(true)->orderbyDesc('updated_at')->first();
        if($f)
            $fecha= $f->updated_at;
        return $fecha;
    }





    public function comprador()
    {
        return $this->belongsTo(\App\Models\Comprador::class, 'comprador_id');
    }
}
