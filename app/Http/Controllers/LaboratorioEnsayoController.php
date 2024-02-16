<?php

namespace App\Http\Controllers;

use App\Models\Lab\Ensayo;
use App\Models\Laboratorio;
use App\Models\LaboratorioEnsayo;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LaboratorioEnsayoController extends AppBaseController
{

    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $ensayos = LaboratorioEnsayo::
        whereBetween('fecha_finalizacion', [$fecha_inicial, $fecha_final])
            ->where(function ($q) use ($txtBuscar) {
                $q->where('codigo', 'ilike', "%{$txtBuscar}%")
                    ->orWhereHas('formularioLiquidacion', function ($q) use ($txtBuscar) {
                        $q->whereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)",
                            ["%{$txtBuscar}%"]);
                    });
            })
            ->orderByDesc('fecha_finalizacion')
            ->paginate(16);

        return view('ensayos.index')
            ->with('ensayos', $ensayos);
    }

    public function generarBoletaEnsayo($id)
    {
        $ensayo = LaboratorioEnsayo::find($id);
        $laboratorios = Laboratorio::whereLaboratorioEnsayoId($id)->get();

        //generador qr
        $urlQR = url("/api/informe-ensayo/{$ensayo->id}");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));

        $vistaurl = "reportes.informe_laboratorio";
//        $view = PDF::loadView('$vistaurl', $formularioLiquidacion, $qrcode); // \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render();
        $view = \View::make($vistaurl, compact('ensayo', 'laboratorios', 'qrcode'))->render(); //PDF::loadView('reportes.pesaje', $formularioLiquidacion, $qrcode);

        $pdf= \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $fechaImpresion = date('d/m/Y H:i');
        if (!is_null($ensayo->fecha_finalizacion))
            $fechaImpresion = date('d/m/Y H:i', strtotime($ensayo->fecha_finalizacion));
        $canvas->page_text(350, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . $fechaImpresion, null, 7, array(0, 0, 0));


        $file_url = public_path() . "/documents/" . $ensayo->formularioLiquidacion->id . '.pdf';
        file_put_contents($file_url, $pdf->output());
//        return $pdf->stream('Pesaje ' . $formularioLiquidacion->lote . '.pdf');
        return $pdf->output();

       // return $pdf->stream('InformeEnsayo ' . $ensayo->lote . '.pdf');
    }

}
