<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Models\DocumentoCompra;
use App\Models\FormularioLiquidacion;
use App\Models\PagoMovimiento;
use App\Patrones\Estado;
use App\Patrones\Fachada;
use App\Patrones\TipoPago;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\File;
use Luecano\NumeroALetras\NumeroALetras;
use DB;
use Karriere\PdfMerge\PdfMerge;

class CajaController extends AppBaseController
{
    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 3 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $esCancelado = $request->txtEstado;
        $fechaFiltro = 'fecha_liquidacion';

        if (is_null($esCancelado)) {
            $esCancelado = false;
        }
        if ($esCancelado) {
            $fechaFiltro = 'fecha_cancelacion';
            $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        }

        $formularioLiquidacions = FormularioLiquidacion::
            whereIn('estado',[Estado::Liquidado, Estado::Composito, Estado::Vendido])
            ->where('es_cancelado', $esCancelado)
//            ->where('saldo_favor', '>', '0')
            ->where(function ($q) use ($txtBuscar) {
                $q->where('producto', 'ilike', "%{$txtBuscar}%")
                    ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                    ->orWhereHas('cliente', function ($q) use ($txtBuscar) {
                        $q->where('nombre', 'ilike', "%{$txtBuscar}%")
                            ->orWhere('nit', 'ilike', "%{$txtBuscar}%")
                            ->orWhereHas('cooperativa', function ($q) use ($txtBuscar) {
                                $q->where('razon_social', 'ilike', "%{$txtBuscar}%");
                            });
                    });
            })
            ->whereBetween($fechaFiltro, [$fecha_inicial, $fecha_final])
            ->orderByDesc('fecha_hora_liquidacion')
            ->paginate(50);

        return view('cajas.index')
            ->with('formularioLiquidacions', $formularioLiquidacions)->with('esCancelado', $esCancelado);
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $formularioId = $request->idFormulario;
            $fecha = date('Y-m-d');

            $formulario = FormularioLiquidacion::find($formularioId);

            if ($formulario->es_cancelado) {
                \DB::rollBack();
                Flash::error('La liquidación ya fue pagada anteriormente.');
                return redirect(route('cajas.index'));
            }

            $obj = new RetencionPagoController();
            $obj->registrar($formulario);

            FormularioLiquidacion::where('id', $formularioId)->update(['es_cancelado' => true, 'fecha_cancelacion' => $fecha]);
            $campos['monto'] = $formulario->saldo_favor < 0.00 ? 0: $formulario->saldo_favor;
            $campos['origen_type'] = FormularioLiquidacion::class;
            $campos['origen_id'] = $formularioId;
            $campos['metodo'] = $request->metodo;
            if ($request->metodo == TipoPago::CuentaBancaria){
                $campos['banco'] = $request->banco;
                $campos['glosa'] = 'Liquidación para Lote ' . $formulario->lote . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
            }
            else
                $campos['glosa'] = 'Liquidación para Lote ' . $formulario->lote;
            $obj = new MovimientoController();

            $campos['codigo'] = $obj->getCodigo('Egreso');
            $campos['anio'] = date('y');
            if(date('m')>=10)
                $campos['anio'] =$campos['anio'] +1;

            $objMov= new MovimientoController();
            $campos['numero'] = $objMov->proximoOrden();
            PagoMovimiento::create($campos);

            event(new AccionCompleta("Pagado", "Formulario pagado", $formularioId));

            //adjuntar formulario a documentos
            $objRep = new ReporteController();
            $objRep->generarFormularioLiquidacion($formularioId);
            $this->subirDocumento($formulario);

            if(is_null($formulario->url_documento)){
                $formulario->url_documento = $formulario->id.'_document.pdf';
                $formulario->save();
            }

            DocumentoCompra::whereFormularioLiquidacionId($formularioId)->whereDescripcion(\App\Patrones\DocumentoCompra::Formulario)
                ->update(['agregado' => true]);

            \DB::commit();

            Flash::success('Liquidación pagada correctamente.');

            echo "<script>
            window.location.href = '/cajas';
            window.open('/imprimirFormulario/'+'$formularioId', '_blank');
                </script>";
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function subirDocumento($formularioLiquidacion)
    {
        $nombreArchivoForm= public_path() .'/documents/'.$formularioLiquidacion->id.'.pdf';
        //unir varios pdf's en uno
        $pdf = new \PDFMerger;
        $pdf->addPDF($nombreArchivoForm, 'all');

        //adjuntando los documentos ya anteriormente registrados
        if (!is_null($formularioLiquidacion->url_documento)) {
            $file_url = public_path() . "/documents/" . $formularioLiquidacion->url_documento;
            if (file_exists($file_url))
                $pdf->addPDF($file_url, 'all');
        }

        //juntando todos los documentos
        $nombreArchivo = $formularioLiquidacion->id . '_document' . '.pdf';
        $pdf->merge('file', public_path() . "/documents/" . $nombreArchivo);
        File::move($nombreArchivoForm, public_path() .'/documents/'.$formularioLiquidacion->id.'.pdf');

        File::delete($nombreArchivoForm);
        return $nombreArchivo;
    }

    public function cambiarNombreDocs(){
        $forms = FormularioLiquidacion::where('fecha_liquidacion', '>=', '2023-04-01')
            ->where('fecha_liquidacion', '<=', '2023-04-30')->whereLetra('G')
            ->whereIn('estado', ['Liquidado', 'Composito', 'Vendido'])->get();

        foreach ($forms as $form){
            $archivo1= public_path() .'/documents/ventas/'.$form->id.'_document.pdf';
            $archivo2= public_path() .'/documents/docg/'.$form->lote_sin_gestion.'.pdf';
            File::move($archivo1, $archivo2);
        }
    }

    public function cambiarNombreDocsComplejo(){
        $forms = FormularioLiquidacion::where('fecha_liquidacion', '>=', '2023-04-01')
            ->where('fecha_liquidacion', '<=', '2023-04-30')
            ->whereIn('estado', ['Liquidado', 'Composito', 'Vendido'])
            ->whereIn('letra', ['A', 'B', 'C'])->get();
        foreach ($forms as $form){
            $archivo1= public_path() .'/documents/ventas/'.$form->id.'_document.pdf';
            $archivo2= public_path() .'/documents/docabc/'.$form->lote_sin_gestion.'.pdf';
            File::move($archivo1, $archivo2);
        }
    }

    public function getCodigoCaja($id, $origen)
    {
        $pago = PagoMovimiento::whereOrigenType($origen)->whereOrigenId($id)->whereAlta(true)->first();
        if ($pago)
            return $pago->codigo;
        else
            return '';
    }

    public function comprobante()
    {
        return view('cajas.comprobante');
    }

    public function imprimirComprobante(Request $request)
    {
        $fecha = $request->fecha;
        $formularios = FormularioLiquidacion::where(DB::raw("to_char(fecha_cancelacion, 'YYYY-MM-DD')"), $fecha)
            ->orderBy('fecha_cancelacion')->get();
        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney(abs($formularios->sum('saldo_favor')), 2, 'BOLIVIANOS', 'CENTAVOS');

        $vistaurl = "cajas.imprimir";
        $view = \View::make($vistaurl, compact('formularios', 'fecha', 'literal'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $canvas->page_text(360, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . date('d/m/Y H:i'), null, 7, array(0, 0, 0));

        return $pdf->stream('ComprobanteCaja ' . $fecha . '.pdf');
    }


}
