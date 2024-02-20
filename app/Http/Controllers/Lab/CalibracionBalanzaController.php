<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Lab\CalibracionBalanza;
use Illuminate\Http\Request;
use Flash;
use DB;

class CalibracionBalanzaController extends AppBaseController
{
    public function index(Request $request)
    {

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fecha_fin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $tipo=$request->tipo;
        $calibraciones = CalibracionBalanza::
        whereTipo($tipo)
            ->whereBetween('created_at', [$fecha_inicial, $fecha_fin])
            ->orderBy("created_at")
            ->paginate(100);

        return view('lab.calibraciones_balanzas.index')
            ->with('calibraciones', $calibraciones)->with('tipo', $tipo)
            ->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);

    }

    public function store(Request $request)
    {
        $input = $request->all();

        CalibracionBalanza::create($input);

        Flash::success('Calibración de balanza guardada correctamente.');

        return redirect(url('lab/calibraciones-balanzas?tipo='.$request->tipo));

    }

    public function actualizar(Request $request)
    {
        $id = $request->id;
        $calibracion = CalibracionBalanza::find($id);

        if (empty($calibracion)) {
            Flash::error('Calibración no encontrada.');

            return redirect(url('lab/calibraciones-balanzas?tipo='.$calibracion->tipo));
        }

        $input = $request->all();
        $calibracion->update($input);
        Flash::success('Calibración guardada correctamente.');

        return redirect(url('lab/calibraciones-balanzas?tipo='.$calibracion->tipo));

    }


}
