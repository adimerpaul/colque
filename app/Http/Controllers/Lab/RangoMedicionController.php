<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Lab\RangoMedicion;
use Illuminate\Http\Request;
use Flash;
use DB;

class RangoMedicionController extends AppBaseController
{
    public function index(Request $request)
    {

        $rangos = RangoMedicion::
        orderBy('tipo')->paginate();
        return view('lab.rangos_mediciones.index')
            ->with('rangos', $rangos);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        RangoMedicion::create($input);

        Flash::success('Rango guardado correctamente.');

        return redirect(route('rangos-mediciones.index'));

    }

    public function actualizar(Request $request)
    {
        $id = $request->id;
        $rango = RangoMedicion::find($id);

        if (empty($rango)) {
            Flash::error('Rango no encontrado.');

            return redirect(route('rangos-mediciones.index'));
        }

        $input = $request->all();
        $rango->update($input);
        Flash::success('Rango guardado correctamente.');

        return redirect(route('rangos-mediciones.index'));

    }


}
