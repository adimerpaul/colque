<?php

namespace App\Models;


use App\Http\Controllers\VentaController;
use App\Patrones\EstadoVenta;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
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
        'url_documento',
        'transporte',
        'otros_costos',
        'lote_comprador',
        'tipo_transporte',
        'trayecto',
        'tranca',
        'municipio',
        'empaque',
        'fecha_venta',
        'fecha_cobro',
        'estado',
        'producto',
        'comprador_id',
        'tipo',
        'es_cancelado',
        'monto',
        'a_operaciones',
        'peso_neto_seco',
        'valor_neto_venta',
        'utilidad',
        'margen',
        'es_aprobado',
        'tipo_lote',
        'es_despachado',
        'fecha_promedio',
        'monto_final',
        'tipo_factura',
        'verificado_despacho'

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
        'es_escritura',
        'suma_peso_neto_seco',
        'suma_valor_neto_venta',
        'suma_peso_neto_humedo',
        'suma_peso_bruto_humedo',
        'suma_peso_fino_sn',
        'suma_peso_fino_pb',
        'suma_peso_fino_ag',
        'suma_peso_fino_au',
        'suma_peso_fino_sb',
        'suma_peso_fino_zn',
        'suma_cotizacion_sn',
        'suma_cotizacion_pb',
        'suma_cotizacion_ag',
        'suma_cotizacion_au',
        'suma_cotizacion_sb',
        'suma_cotizacion_zn',
        'humedad_compras',
        'documento_que_falta',
        'total_anticipos',
        'falta_documento',
        'faltan_pesajes',
        'fecha_recepcion',
        'costos_otros',
        'fecha_despacho',
    ];

    public function getLoteAttribute()
    {
        return sprintf("%s%d%s/%s", $this->sigla, $this->numero_lote, $this->letra, substr($this->anio, 2, 2));
    }

    public function getLoteSinGestionAttribute()
    {
        return sprintf("%s%d%s", $this->sigla, $this->numero_lote, $this->letra);
    }

    public function getCodigoOddAttribute()
    {
        return sprintf("%s%d/%s", 'ODD', $this->numero_lote, substr($this->anio, 2, 2));
    }

    public function getCodigoOdvAttribute()
    {
        return sprintf("%s%d/%s", 'ODV', $this->numero_lote, substr($this->anio, 2, 2));
    }

    public function getEsEscrituraAttribute()
    {
        return $this->estado === EstadoVenta::EnProceso;
    }

    public function getOnlyReadAttribute()
    {
        return $this->estado === EstadoVenta::EnProceso ? "" : "pointer-events: none;";
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


    public function getMermaComprasAttribute()
    {
        $obj = new VentaController();
        $formularios = $obj->getCompras($this->id);
        $merma = $formularios->sum('merma_kg');

        return $merma;
    }

    public function getFaltaDocumentoAttribute()
    {
        $falta = false;
        $contador = DocumentoVenta::whereVentaId($this->id)->where('agregado', false)->where('descripcion', '<>', 'Otros')->count();
        if ($contador > 0)
            $falta = true;
        return $falta;
    }

    public function getFaltanPesajesAttribute()
    {
        $falta = true;

        $contadorPesaje = PesajeVenta::whereVentaId($this->id)->count();
        if ($contadorPesaje > 0)
            $falta = false;
        return $falta;
    }


    public function getDocumentoQueFaltaAttribute()
    {
        $lista = '';
        $documentos = DocumentoVenta::whereVentaId($this->id)->where('agregado', false)->get();
        foreach ($documentos as $documento) {
            if ($lista === '')
                $lista = $documento->descripcion;
            else
                $lista = $lista . ', ' . $documento->descripcion;
        }
        return $lista;
    }

    public function getFechaRecepcionAttribute()
    {
        $form=FormularioLiquidacion::
        join('venta_formulario_liquidacion', 'formulario_liquidacion.id', '=', 'venta_formulario_liquidacion.formulario_liquidacion_id')
            ->where('venta_formulario_liquidacion.venta_id', $this->id)
            ->select("formulario_liquidacion.*")
            ->orderByDesc('formulario_liquidacion.fecha_liquidacion')
            ->first();
        return $form->fecha_liquidacion;
    }

    public function getFechaInicialAttribute()
    {
        $form=FormularioLiquidacion::
        join('venta_formulario_liquidacion', 'formulario_liquidacion.id', '=', 'venta_formulario_liquidacion.formulario_liquidacion_id')
            ->where('venta_formulario_liquidacion.venta_id', $this->id)
            ->select("formulario_liquidacion.*")
            ->orderBy('formulario_liquidacion.fecha_liquidacion')
            ->first();
        return $form->fecha_liquidacion;
    }

    public function getEstaDespachadoAttribute()
    {
        $compras = VentaFormularioLiquidacion::whereVentaId($this->id)->count();
        if($compras==0)
            return false;

        $contador = VentaFormularioLiquidacion::whereVentaId($this->id)->whereDespachado(false)->count();
        $despachado = true;
        if($contador>0)
            $despachado = false;

        return $despachado;
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

    public function getPeriodoRotacionAttribute(){

        if($this->estado!=EstadoVenta::Liquidado)
            return 0;

        $fechaP = Carbon::parse($this->fecha_promedio);
        $fechaV = Carbon::parse($this->fecha_venta);

        $dias = $fechaV->diffInDays($fechaP);

        return $dias;
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
