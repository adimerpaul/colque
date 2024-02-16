<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Lab\Accidente;
use Illuminate\Http\Request;
use Flash;
use DB;

class AccidenteController extends AppBaseController
{
    public function index(Request $request)
    {
        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $accidentes = Accidente::
            whereBetween('fecha', [$fecha_inicial, $fecha_final])
        ->orderBy('fecha')->paginate(100);
        return view('lab.accidentes.index')
            ->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final)
            ->with('accidentes', $accidentes);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        Accidente::create($input);

        Flash::success('Accidente guardado correctamente.');

        return redirect(route('accidentes.index'));

    }

    public function actualizar(Request $request)
    {
        $id = $request->id;
        $accidente = Accidente::find($id);

        if (empty($accidente)) {
            Flash::error('Accidente no encontrado.');

            return redirect(route('accidentes.index'));
        }

        $input = $request->all();
        $accidente->update($input);
        Flash::success('Accidente guardado correctamente.');

        return redirect(route('accidentes.index'));

    }
    public function registrarSinAccidentes(){
        $hoy = date('Y-m-d');
        $ultimo = Accidente::orderByDesc('fecha')->first();
        $ultimo= $ultimo->fecha;
        $date1 = new \DateTime($ultimo);
        $date2 = new \DateTime($hoy);
        $diff = $date1->diff($date2)->days;

        for($i = 0; $i < ($diff-1); $i++){
            $ultimo= date('Y-m-d', strtotime($ultimo. ' + 1 days'));
            $accidente['tipo'] = 'Sin Accidente';
            $accidente['descripcion'] = 'Sin Accidente';
            $accidente['fecha'] = $ultimo;
            $accidente['hora'] = '16:30:00';
            Accidente::create($accidente);
        }
    }
}
