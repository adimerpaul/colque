<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Lab\ConstanteMedida;
use Illuminate\Http\Request;
use Flash;
use DB;

class ConstanteMedidaController extends AppBaseController
{
    public function index(Request $request)
    {

        $constantes = ConstanteMedida::
        orderBy('tipo')->paginate();
        return view('lab.constantes_medidas.index')
            ->with('constantes', $constantes);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        ConstanteMedida::create($input);

        Flash::success('Constante guardada correctamente.');

        return redirect(route('constantes-medidas.index'));

    }

    public function actualizar(Request $request)
    {
        $id = $request->id;
        $constante = ConstanteMedida::find($id);

        if (empty($constante)) {
            Flash::error('Constante no encontrada.');

            return redirect(route('constantes-medidas.index'));
        }

        $input = $request->all();
        $constante->update($input);
        Flash::success('Constante guardada correctamente.');

        return redirect(route('constantes-medidas.index'));

    }


}
