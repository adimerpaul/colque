<?php

namespace App\Http\Controllers;

use App\Models\PagoDolar;
use App\Models\PagoMovimiento;
use Illuminate\Http\Request;
use Flash;
use Luecano\NumeroALetras\NumeroALetras;
use Response;

class PagoDolarController extends AppBaseController
{

    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 weeks'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFinal = $fecha_final;

        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $pagos = PagoDolar::
 whereBetween('created_at', [$fecha_inicial, $fecha_final])
            ->orderBy('id')
            ->paginate(50);

        return view('pago_dolares.index')->with('pagos', $pagos)
            ->with('fechaInicial', $fecha_inicial)->with('fechaFinal', $fechaFinal);
    }

    public function create()
    {
        return view('pago_dolares.create');
    }

    public function store(Request $request)
    {
        // if (auth()->user()->personal->es_jefe) {
        $input = $request->all();
        $input['codigo'] = $this->getCodigo($request->tipo);
        $input['anio'] = date('y');
        if (date('m') >= 10)
            $input['anio'] = $input['anio'] + 1;
        //$input['glosa'] = 'BANCOS BNB M/E. '. $request->glosa. ', en transferencia bancaria con recibo ' . $request->numero_recibo;
        $input['glosa'] = trim(sprintf("%s%s%s%s", 'BANCOS BNB M/E. ', $request->glosa, ', en transferencia bancaria con recibo ', $request->numero_recibo));


        $pago = PagoDolar::create($input);

        Flash::success('Pago registrado correctamente.');

        echo "<script>
            window.location.href = '/pagos-dolares';
            window.open('/pagos-dolares/'+'$pago->id'+'/imprimir', '_blank');
                </script>";

    }

    public function imprimir($pagoId)
    {

        $pago = PagoDolar::find($pagoId);
        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($pago->monto, 2, 'DÓLARES', 'CENTAVOS');

//        $historial = PagoMovimiento::whereOrigenId($pago->origen_id)->whereAlta(true)->whereOrigenType($pago->origen_type)
//            ->where('id', '<=', $pago->id)->get();
        $vistaurl = "pago_dolares.imprimir";
        $view = \View::make($vistaurl, compact('pago', 'literal'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboPago-' . $pago->codigo . '.pdf');
    }

    public function getCodigo($tipo)
    {
        $anio = date('Y');
        $mes = date('m');
        if ($mes < '10') {
            $fechaFin = date('Y-m-d H:i:s', strtotime($anio . '-09-30 23:59:59'));
            $anio = date('Y', strtotime($anio . ' - 1 years'));
            $fechaInicio = date('Y-m-d H:i:s', strtotime($anio . '-10-01 00:00:00'));
        } else {
            $fechaInicio = date('Y-m-d H:i:s', strtotime($anio . '-10-01 00:00:00'));
            $anio = date('Y', strtotime($anio . ' + 1 years'));
            $fechaFin = date('Y-m-d H:i:s', strtotime($anio . '-09-30 23:59:59'));
        }
        $tipo = substr($tipo, 0, 1);
        $contador = PagoDolar::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('codigo', 'ilike', "%{$tipo}%")
            ->orderByDesc('id')
            ->select('codigo')
            ->first();

        $numero = '00001';

        if ($contador) {
            $contador = substr($contador->codigo, 3);
            $contador = $contador + 1;
            $numero = str_pad($contador, 5, "0", STR_PAD_LEFT);
        }

        return (sprintf("%s%s%s", 'CD', $tipo, $numero));
    }
}
