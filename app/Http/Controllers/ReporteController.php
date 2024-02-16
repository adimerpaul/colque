<?php

namespace App\Http\Controllers;

use App\Models\Anticipo;
use App\Models\Bono;
use App\Models\CambioFormulario;
use App\Models\Concentrado;
use App\Models\ContratoPlantilla;
use App\Models\CotizacionDiaria;
use App\Models\FormularioDescuento;
use App\Models\PagoMovimiento;
use App\Patrones\AccionCambioFormulario;
use App\Patrones\ClaseDescuento;
use App\Patrones\Contrato;
use App\Patrones\Estado;
use App\Patrones\Fachada;
use App\Patrones\Rol;
use App\Patrones\TipoLoteVenta;
use Illuminate\Http\Request;
use App\Models\FormularioLiquidacion;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DB;

class ReporteController extends Controller
{
    public function getPesaje($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($id);
        if (empty($formularioLiquidacion)) {
            return response()->json(['msg' => 'Formulario Liquidación no encontrado']);
        }
        //generador qr
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($this->generarQr($formularioLiquidacion)));

        $vistaurl = "reportes.pesaje_qr";
        $view = \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
//        $pdf->setPaper(array(0,0,212.60,143.73));
        $pdf->setPaper(array(0, 0, 141.73, 73.87));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        return $pdf->stream('Pesaje' . $formularioLiquidacion->lote . '.pdf');

    }

    public function getPesajeDescarga($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($id);
        if (empty($formularioLiquidacion)) {
            return response()->json(['msg' => 'Formulario Liquidación no encontrado']);
        }

        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($this->generarQr($formularioLiquidacion)));

        $vistaurl = "reportes.pesaje_qr";
        $view = \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper(array(0, 0, 141.73, 83.87));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        return $pdf->download('Pesaje' . $formularioLiquidacion->lote . '.pdf');
    }

    private function generarQr($formularioLiquidacion)
    {
        //generador qr
        $urlQR =
            $formularioLiquidacion->lote .
            "\n- PRODUCTOR: " . $formularioLiquidacion->cliente->cooperativa->razon_social .
            "\n- CLIENTE: " . $formularioLiquidacion->cliente->nombre .
//            "\n- CI/NIT: ".$formularioLiquidacion->cliente->nit.
//            "\n- SACOS: ".$formularioLiquidacion->sacos.
            "\n- PBH: " . $formularioLiquidacion->peso_bruto .
//            "\n- TARA: ".$formularioLiquidacion->tara .
            "\n- PNH: " . $formularioLiquidacion->peso_neto .
            "\n- FECHA RECEPCION: " . date('d/m/Y H:i', strtotime($formularioLiquidacion->created_at));
        return $urlQR;
    }

    public function imprimirBoletaPesaje($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($id);
        if (empty($formularioLiquidacion)) {
            return response()->json(['msg' => 'Formulario Liquidación no encontrado']);
        }
        //generador qr
        $urlQR = url("/reporte_pesaje/{$formularioLiquidacion->id}");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));

        $vistaurl = "reportes.pesaje_adjunto";
//        $view = PDF::loadView('$vistaurl', $formularioLiquidacion, $qrcode); // \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render();
        $view = \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render(); //PDF::loadView('reportes.pesaje', $formularioLiquidacion, $qrcode);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper(array(0, 0, 595.28, 419.53));
//        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $file_url = public_path() . "/documents/" . $id . '.pdf';
        file_put_contents($file_url, $pdf->output());
        return $pdf->stream('Pesaje' . $formularioLiquidacion->lote . '.pdf');
//        return $pdf->output();
    }


    public function generarBoletaPesaje($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($id);
        if (empty($formularioLiquidacion)) {
            return response()->json(['msg' => 'Formulario Liquidación no encontrado']);
        }
        //generador qr
        $urlQR = url("/reporte_pesaje/{$formularioLiquidacion->id}");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));

        $vistaurl = "reportes.pesaje_adjunto";
//        $view = PDF::loadView('$vistaurl', $formularioLiquidacion, $qrcode); // \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render();
        $view = \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render(); //PDF::loadView('reportes.pesaje', $formularioLiquidacion, $qrcode);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
//        $pdf->setPaper('a4', 'portrait');
        $pdf->setPaper(array(0, 0, 595.28, 419.53));
        //$pdf->setPaper(array(0,0,595.27,441.89));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $file_url = public_path() . "/documents/" . $id . '.pdf';
        file_put_contents($file_url, $pdf->output());
//        return $pdf->stream('Pesaje ' . $formularioLiquidacion->lote . '.pdf');
        return $pdf->output();
    }

    public function reemplazarContrato($formulario)
    {
        $contrato = ContratoPlantilla::find($formulario->contrato_plantilla_id);
        $contrato = $contrato->descripcion;

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney(abs($formulario->totales['total_liquidacion']), 2, 'BOLIVIANOS', 'CENTAVOS');

        $contrato = str_replace(Contrato::Producto, $formulario->producto, $contrato);
        $contrato = str_replace(Contrato::Lote, $formulario->lote, $contrato);
        $contrato = str_replace(Contrato::DocumentoProductor, $formulario->cliente->nit, $contrato);
        $contrato = str_replace(Contrato::PesoBruto, $formulario->peso_bruto, $contrato);
        $contrato = str_replace(Contrato::LiquidoPabable, number_format($formulario->totales['total_liquidacion'], 2), $contrato);
        $contrato = str_replace(Contrato::Literal, $literal, $contrato);
        $contrato = str_replace(Contrato::Fecha, Fachada::getFechaLiteral($formulario->fecha_liquidacion), $contrato);
        $contrato = str_replace(Contrato::Leyes, $formulario->ley_producto, $contrato);
        $contrato = str_replace(Contrato::Humedad, 'H20: ' . $formulario->humedad . ' %', $contrato);
        $contrato = str_replace(Contrato::NombreProductor, $formulario->cliente->nombre, $contrato);

        return $contrato;
    }

    public function agregarFirma($firmaProductor, $contrato)
    {
        $firma =
            '<div style="width: 100%" ><p style="text-align:justify;line-height:150%;"><strong><span style="font-size:15px;line-height:150%;">
                <img style="float: left" src="' . 'firmas/' . $firmaProductor . '" width="250" height="100" /> </span></strong></p></div><br><br><br>' .
            '<div style="width: 100%; margin-top: 0.2cm" ><p style="line-height: 107%; font-size: 15px; font-family: \'Calibri\',sans-serif; text-align: right; margin: 0cm 0cm 8.0pt 0cm;"><span style="font-size: 16px; line-height: 107%; font-family: \'Century Gothic\',sans-serif;">&nbsp;</span></p>
                <p style="line-height: 107%; font-size: 15px; font-family: \'Calibri\',sans-serif; margin: 0cm 0cm 8.0pt 0cm;"><strong><span style="font-size: 16px; line-height: 107%; font-family: \'Century Gothic\',sans-serif;"> &nbsp;&nbsp;'

            . '</span></strong></p></div>';
        $contrato = str_replace(Contrato::FirmaProductor, $firma, $contrato);

        $firma =
            '<div style="width: 100%" ><p style="text-align:justify;line-height:150%;"><strong><span style="font-size:15px;line-height:150%;">
                <img style="float: left" src="firmas/1666724635.PNG" width="250" height="100" /> </span></strong></p></div><br><br><br>' .
            '<div style="width: 100%; margin-top: 0.2cm" ><p style="line-height: 107%; font-size: 15px; font-family: \'Calibri\',sans-serif; text-align: right; margin: 0cm 0cm 8.0pt 0cm;"><span style="font-size: 16px; line-height: 107%; font-family: \'Century Gothic\',sans-serif;">&nbsp;</span></p>
                <p style="line-height: 107%; font-size: 15px; font-family: \'Calibri\',sans-serif; margin: 0cm 0cm 8.0pt 0cm;"><strong><span style="font-size: 16px; line-height: 107%; font-family: \'Century Gothic\',sans-serif;"> &nbsp;&nbsp;'

            . '</span></strong></p></div>';
        $contrato = str_replace(Contrato::FirmaColquechaca, $firma, $contrato);

        return $contrato;
    }

    public function generarContrato($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($id);
        if (empty($formularioLiquidacion)) {
            return response()->json(['msg' => 'Formulario Liquidación no encontrado']);
        }
        //generador qr
        $contrato = $this->reemplazarContrato($formularioLiquidacion);
        $contrato = $this->agregarFirma($formularioLiquidacion->cliente->firma, $contrato);
        $vistaurl = "formulario_liquidacions.contrato";
        $view = \View::make($vistaurl, compact('contrato'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $file_url = public_path() . "/documents/" . $id . '.pdf';
        file_put_contents($file_url, $pdf->output());
        return $pdf->output();
    }

    public function generarFormularioLiquidacion($formulario_id)
    {
        $resumen = $this->getResumen($formulario_id);
        $formularioLiquidacion = $resumen['formulario'];
        $cotizacionesDiarias = $resumen['diarias'];

        $descuentos = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Descuento');
            })->orderBy('descuento_bonificacion_id')->get();
        $bonificaciones = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Bonificacion')
                    ->whereClase(ClaseDescuento::EnLiquidacion)
                ;
            })->orderBy('descuento_bonificacion_id')->get();
        $retenciones = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])
            ->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Retencion');
            })->orderBy('descuento_bonificacion_id')->get();
        $totalRegalias = 0;
        /* $pagable=$formularioLiquidacion->valor_neto_venta - $retenciones->sum('sub_total') - $descuentos->sum('sub_total')+$bonificaciones->sum('sub_total') - $formularioLiquidacion->totales['total_anticipos'];*/

        //generador qr
        $urlQR = url("/imprimirFormulario/{$formulario_id}");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney(abs($formularioLiquidacion->totales['total_saldo_favor']), 2, 'BOLIVIANOS', 'CENTAVOS');
        $sumaBrutoVenta = 0;
        foreach ($formularioLiquidacion->minerales_regalia as $mineral) {
            $sumaBrutoVenta = $mineral['valor_bruto_venta'] + $sumaBrutoVenta;
        }
        $nombre = null;
        $anticipos = Anticipo::whereFormularioLiquidacionId($formulario_id)->orderBy('fecha')->get();
        $bonos = Bono::whereFormularioLiquidacionId($formulario_id)->orderBy('fecha')->get();
        //$totalLiquidacion=$pagable-$formularioLiquidacion->totales['total_anticipos'];
        $vistaurl = "formulario_liquidacions.generar_formulario";
        $view = \View::make($vistaurl, compact('formulario_id', 'formularioLiquidacion', 'bonificaciones', 'descuentos', 'anticipos',
            'retenciones', 'nombre', 'cotizacionesDiarias', 'qrcode', 'totalRegalias', 'literal', 'bonos', 'sumaBrutoVenta'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $fechaImpresion = date('d/m/Y H:i');
        $pago = PagoMovimiento::whereOrigenId($formulario_id)->whereOrigenType(FormularioLiquidacion::class)->whereAlta(true)->first();
        if ($pago)
            $fechaImpresion = date('d/m/Y H:i', strtotime($pago->created_at));
        $canvas->page_text(360, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . $fechaImpresion, null, 7, array(0, 0, 0));


        $file_url = public_path() . "/documents/" . $formulario_id . '.pdf';
        file_put_contents($file_url, $pdf->output());

        return $pdf->output();
    }

    public function getResumen($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::with([
            'tipoCambio',
            'cliente'
        ])->find($id);

        //cotizaciones diarias
        $cotizacionDiaria = $this->getCotizacionDiaria($formularioLiquidacion);

        return ['formulario' => $formularioLiquidacion, 'diarias' => $cotizacionDiaria];
    }

    private function getCotizacionDiaria($f)
    {
        return CotizacionDiaria::with(['mineral:id,simbolo,nombre'])
            ->whereFecha($f->fecha_cotizacion)
            ->whereIn('mineral_id', $f->liquidacioMinerales->pluck('mineral_id'))
            ->orderBy('mineral_id')
            ->get();
    }

    public function getStock(Request $request)
    {
        $txtBuscar = $request->producto_id;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $materiales= FormularioLiquidacion::
        leftJoin('venta_formulario_liquidacion', 'formulario_liquidacion.id', '=', 'venta_formulario_liquidacion.formulario_liquidacion_id')
            ->leftJoin('venta', 'venta.id', '=', 'venta_formulario_liquidacion.venta_id')
            ->where(function ($q) use ($txtBuscar) {
                $q->where('venta_formulario_liquidacion.despachado', false)
                   ->where('formulario_liquidacion.letra', 'like','%'. $txtBuscar .'%');
            })
            ->orWhere(function ($q) use ($txtBuscar)  {
                $q->whereNull('venta_formulario_liquidacion.despachado')
                    ->whereIn('formulario_liquidacion.estado', [Estado::EnProceso, Estado::Liquidado])
                    ->where('formulario_liquidacion.letra', 'like','%'. $txtBuscar .'%');
            })
            ->select(DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3)) as lote_compra"),
                'formulario_liquidacion.peso_seco', 'formulario_liquidacion.neto_venta',
                DB::raw("concat(venta.sigla, venta.numero_lote, venta.letra,'/', SUBSTRING ( venta.anio::text,3)) as lote_de_venta"))
            ->orderBy('formulario_liquidacion.id')
            ->get();

        $ingenios =
            Concentrado::where('tipo_lote', TipoLoteVenta::Ingenio)
                ->where(function ($q) use ($txtBuscar) {
                    if($txtBuscar != '' and $txtBuscar!='%'){
                        $q->where('nombre', 'like','%CM%'.'%-'. $txtBuscar .'%');
                    }
                })
                ->whereDespachado(false)
                ->orderBy('id')->get();

        return view('reportes.stock_actual')
            ->with('materiales', $materiales)
            ->with('ingenios', $ingenios)
            ->with('producto_id', $txtBuscar);

    }

}
