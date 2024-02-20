<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Lab\Accidente;
use App\Models\Lab\CalibracionBalanza;
use App\Models\Lab\Cliente;
use App\Models\Lab\Ensayo;
use App\Models\Lab\PagoMovimiento;
use App\Models\Lab\Recepcion;
use App\Models\Lab\TemperaturaHumedad;
use App\Patrones\EstadoLaboratorio;
use Illuminate\Http\Request;
use DB;
class ReporteController extends AppBaseController
{
    public function getRechazados(Request $request)
    {
        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $pedidos = Recepcion::whereEstado(EstadoLaboratorio::Anulado)
            ->whereBetween('fecha_rechazo', [$fecha_inicial, $fechaFin])
            ->orderBy('fecha_rechazo')
            ->paginate(50);

        return view('lab.reportes.rechazados')->with('pedidos', $pedidos)->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);
    }

    public function getRechazadosPdf($fecha_inicial, $fecha_final)
    {

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $pedidos = Recepcion::whereEstado(EstadoLaboratorio::Anulado)
            ->whereBetween('fecha_rechazo', [$fecha_inicial, $fechaFin])
            ->orderBy('fecha_rechazo')
            ->get();

        $vistaurl = "lab.reportes.rechazados_pdf";
        $view = \View::make($vistaurl, compact('pedidos', 'fecha_inicial', 'fecha_final'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('muestrasRechazadas_' . $fecha_inicial . '.pdf');
    }

    public function getAceptados(Request $request)
    {
        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $ensayos = Ensayo::
        whereHas('recepcion', function ($q) use ($fecha_inicial, $fechaFin) {
            $q->whereIn('estado',[EstadoLaboratorio::EnProceso, EstadoLaboratorio::Finalizado])
                ->whereBetween('fecha_aceptacion', [$fecha_inicial, $fechaFin]);
        })
            ->orderBy('recepcion_id')
            ->paginate(100);

        return view('lab.reportes.aceptados')->with('ensayos', $ensayos)->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);
    }

    public function getAceptadosPdf($fecha_inicial, $fecha_final)
    {
        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $ensayos = Ensayo::
        whereHas('recepcion', function ($q) use ($fecha_inicial, $fechaFin) {
            $q->whereIn('estado',[EstadoLaboratorio::EnProceso, EstadoLaboratorio::Finalizado])
                ->whereBetween('fecha_aceptacion', [$fecha_inicial, $fechaFin]);
        })
            ->orderBy('recepcion_id')
            ->get();

        $vistaurl = "lab.reportes.aceptados_pdf";
        $view = \View::make($vistaurl, compact('ensayos', 'fecha_inicial', 'fecha_final'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('muestrasAceptadas_' . $fecha_inicial . '.pdf');
    }

    public function getFinalizados(Request $request)
    {
        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $elemento_id = $request->elemento_id;
        if(is_null($request->elemento_id))
            $elemento_id= '%';

        $cliente_id = $request->cliente_id;
        if(is_null($request->cliente_id))
            $cliente_id= '%';

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $ensayos = Ensayo::
        whereHas('recepcion', function ($q) use ($elemento_id, $cliente_id){
            if ($cliente_id == '%') {
                $q->whereEstado(EstadoLaboratorio::Finalizado)
                    ->where('elemento_id','ilike', $elemento_id);
            } else {
                $q->whereEstado(EstadoLaboratorio::Finalizado)
                    ->where('cliente_id', $cliente_id)
                    ->where('elemento_id','ilike', $elemento_id);
            }
        })
            ->whereBetween('fecha_finalizacion', [$fecha_inicial, $fechaFin])

        ->orderBy('fecha_finalizacion')
            ->paginate(100);

        return view('lab.reportes.finalizados')->with('ensayos', $ensayos)->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);
    }

    public function getFinalizadosPdf($fecha_inicial, $fecha_final, $elemento_id, $cliente_id)
    {
        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        if($elemento_id=='todo')
            $elemento_id='%';

        $ensayos = Ensayo::
        whereHas('recepcion', function ($q) use ($elemento_id, $cliente_id){
            if ($cliente_id == 'todo') {
                $q->whereEstado(EstadoLaboratorio::Finalizado)
                    ->where('elemento_id','ilike', $elemento_id);
            } else {
                $q->whereEstado(EstadoLaboratorio::Finalizado)
                    ->where('cliente_id', $cliente_id)
                    ->where('elemento_id','ilike', $elemento_id);
            }
        })
            ->whereBetween('fecha_finalizacion', [$fecha_inicial, $fechaFin])
            ->orderBy('fecha_finalizacion')
            ->get();

        $cliente='todo';
        if($cliente_id!='todo'){
            $cliente=Cliente::find($cliente_id);
            $cliente=$cliente->nombre;
        }

        $vistaurl = "lab.reportes.finalizados_pdf";
        $view = \View::make($vistaurl, compact('ensayos', 'fecha_inicial', 'fecha_final', 'cliente'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('muestrasFinalizadas_' . $fecha_inicial . '.pdf');
    }

    public function getCajas(Request $request)
    {
        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $ingresos = PagoMovimiento::
        select(DB::raw("glosa, metodo, tipo,
                    laboratorio.fn_monto_caja(glosa,'$fecha_inicial', '$fecha_final', metodo, 'Ingreso') as sumatoria,
                    laboratorio.fn_cantidad_lotes(glosa, 'Ingreso', metodo,'$fecha_inicial' , '$fecha_final' ) as cantidad
	           "))
            ->whereAlta(true)->whereEsCancelado(true)->whereTipo('Ingreso')
            ->where('fecha', '>=',$fecha_inicial)
            ->where('fecha', '<=',$fechaFin)
            ->groupBy('glosa', 'metodo', 'tipo')
            ->orderBy("glosa")
            ->paginate(100);

        $egresos = PagoMovimiento::
        select(DB::raw("glosa, metodo, tipo,
                    laboratorio.fn_monto_caja(glosa,'$fecha_inicial', '$fecha_final', metodo, 'Egreso') as sumatoria
	           "))
            ->whereAlta(true)->whereEsCancelado(true)->whereTipo('Egreso')
            ->where('fecha', '>=',$fecha_inicial)
            ->where('fecha', '<=',$fechaFin)
            ->groupBy('glosa', 'metodo', 'tipo')
            ->orderBy("glosa")
            ->paginate(100);

        return view('lab.reportes.cajas')->with('ingresos', $ingresos)->with('egresos', $egresos)->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);
    }

    public function getCajasPdf($fecha_inicial, $fecha_final)
    {
        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $ingresos = PagoMovimiento::
        select(DB::raw("glosa, metodo, tipo,
                    laboratorio.fn_monto_caja(glosa,'$fecha_inicial', '$fecha_final', metodo, 'Ingreso') as sumatoria,
                    laboratorio.fn_cantidad_lotes(glosa, 'Ingreso', metodo,'$fecha_inicial' , '$fecha_final' ) as cantidad
	           "))
            ->whereAlta(true)->whereEsCancelado(true)->whereTipo('Ingreso')
            ->where('fecha', '>=',$fecha_inicial)
            ->where('fecha', '<=',$fechaFin)
            ->groupBy('glosa', 'metodo', 'tipo')
            ->orderBy("glosa")
            ->get();

        $egresos = PagoMovimiento::
        select(DB::raw("glosa, metodo, tipo,
                    laboratorio.fn_monto_caja(glosa,'$fecha_inicial', '$fecha_final', metodo, 'Egreso') as sumatoria
	           "))
            ->whereAlta(true)->whereEsCancelado(true)->whereTipo('Egreso')
            ->where('fecha', '>=',$fecha_inicial)
            ->where('fecha', '<=',$fechaFin)
            ->groupBy('glosa', 'metodo', 'tipo')
            ->orderBy("glosa")
            ->get();

        $vistaurl = "lab.reportes.cajas_pdf";
        $view = \View::make($vistaurl, compact('ingresos', 'egresos', 'fecha_inicial', 'fecha_final'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('ingresosCaja_' . $fecha_inicial . '.pdf');
    }

    public function getIngresos(Request $request)
    {
        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $pagos = PagoMovimiento::
        select(DB::raw("glosa, metodo, tipo,
                    laboratorio.fn_monto_caja(glosa,'$fecha_inicial', '$fecha_final', metodo, 'Ingreso') as sumatoria,
                    laboratorio.fn_cantidad_lotes(glosa, 'Ingreso', metodo,'$fecha_inicial' , '$fecha_final' ) as cantidad
	           "))
            ->whereAlta(true)->whereEsCancelado(true)->whereTipo('Ingreso')
            ->where('fecha', '>=',$fecha_inicial)
            ->where('fecha', '<=',$fechaFin)
            ->groupBy('glosa', 'metodo', 'tipo')
            ->orderBy("glosa")
            ->paginate(100);

        return view('lab.reportes.ingresos')->with('pagos', $pagos)->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);

    }



    public function getIngresosPdf($fecha_inicial, $fecha_final)
    {
        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $pagos = PagoMovimiento::
        select(DB::raw("glosa, metodo, tipo,
                    laboratorio.fn_monto_caja(glosa,'$fecha_inicial', '$fecha_final', metodo, 'Ingreso') as sumatoria,
                    laboratorio.fn_cantidad_lotes(glosa, 'Ingreso', metodo,'$fecha_inicial' , '$fecha_final' ) as cantidad
	           "))
            ->whereAlta(true)->whereEsCancelado(true)->whereTipo('Ingreso')
            ->where('fecha', '>=',$fecha_inicial)
            ->where('fecha', '<=',$fechaFin)
            ->groupBy('glosa', 'metodo', 'tipo')
            ->orderBy("glosa")
            ->get();

        $vistaurl = "lab.reportes.ingresos_pdf";
        $view = \View::make($vistaurl, compact('pagos', 'fecha_inicial', 'fecha_final'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('ingresosCaja_' . $fecha_inicial . '.pdf');
    }

    public function getEgresos(Request $request)
    {
        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $pagos = PagoMovimiento::
        select(DB::raw("glosa, metodo, tipo,
                    laboratorio.fn_monto_caja(glosa,'$fecha_inicial', '$fecha_final', metodo, 'Egreso') as sumatoria
	           "))
            ->whereAlta(true)->whereEsCancelado(true)->whereTipo('Egreso')
            ->where('fecha', '>=',$fecha_inicial)
            ->where('fecha', '<=',$fechaFin)
            ->groupBy('glosa', 'metodo', 'tipo')
            ->orderBy("glosa")
            ->paginate(100);

        return view('lab.reportes.egresos')->with('pagos', $pagos)->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);

    }

    public function getEgresosPdf($fecha_inicial, $fecha_final)
    {
        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $pagos = PagoMovimiento::
        select(DB::raw("glosa, metodo, tipo,
                    laboratorio.fn_monto_caja(glosa,'$fecha_inicial', '$fecha_final', metodo, 'Egreso') as sumatoria
	           "))
            ->whereAlta(true)->whereEsCancelado(true)->whereTipo('Egreso')
            ->where('fecha', '>=',$fecha_inicial)
            ->where('fecha', '<=',$fechaFin)
            ->groupBy('glosa', 'metodo', 'tipo')
            ->orderBy("glosa")
            ->get();

        $vistaurl = "lab.reportes.egresos_pdf";
        $view = \View::make($vistaurl, compact('pagos', 'fecha_inicial', 'fecha_final'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('egresosCaja_' . $fecha_inicial . '.pdf');
    }

    public function getCalibracionesPdf($tipo, $fecha_inicial, $fecha_final)
    {

        $fecha_fin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $calibraciones = CalibracionBalanza::
        whereTipo($tipo)
            ->whereBetween('created_at', [$fecha_inicial, $fecha_fin])
            ->orderBy("created_at")
            ->get();

        $vistaurl = "lab.reportes.calibraciones_pdf";
        $view = \View::make($vistaurl, compact('calibraciones', 'fecha_inicial', 'fecha_final', 'tipo'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('calibracionesBalanza_' . $fecha_inicial . '.pdf');

    }


    public function getTemperaturasPdf($ambiente, $fecha_inicial, $fecha_final)
    {

        $fecha_fin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $temperaturas = TemperaturaHumedad::
        whereAmbiente($ambiente)
            ->whereBetween('created_at', [$fecha_inicial, $fecha_fin])
            ->orderBy('created_at')
            ->get();

        $vistaurl = "lab.reportes.temperaturas_pdf";
        $view = \View::make($vistaurl, compact('temperaturas', 'fecha_inicial', 'fecha_final', 'ambiente'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('temperaturasHumedades_' . $fecha_inicial . '.pdf');

    }

    public function getAccidentesPdf($fecha_inicial, $fecha_final)
    {
        $accidentes = Accidente::
        whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->orderBy('fecha')
            ->get();

        $vistaurl = "lab.reportes.accidentes_pdf";
        $view = \View::make($vistaurl, compact('accidentes', 'fecha_inicial', 'fecha_final'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('accidentes_' . $fecha_inicial . '.pdf');

    }

    public function getReporteTecnico(Request $request)
    {
        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $elemento_id = $request->elemento_id;
        if(is_null($request->elemento_id))
            $elemento_id= 1;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $ensayos = Ensayo::
        whereHas('recepcion', function ($q) use ($elemento_id){
            $q->whereEstado(EstadoLaboratorio::Finalizado)
                ->where('elemento_id','ilike', $elemento_id);
        })
            ->whereBetween('fecha_finalizacion', [$fecha_inicial, $fechaFin])

            ->orderBy('fecha_finalizacion')
            ->paginate(100);

        return view('lab.reportes.tecnico')->with('ensayos', $ensayos)->with('fecha_inicial', $fecha_inicial)
            ->with('fecha_final', $fecha_final)->with('elemento_id', $elemento_id);
    }
}
