<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Lab\FactorVolumetrico;
use Illuminate\Http\Request;
use Flash;
class FactorVolumetricoController extends AppBaseController
{
    public function index(Request $request)
    {

        $factores = FactorVolumetrico::
        orderByDesc('created_at')->paginate();
        return view('lab.factores.index')
            ->with('factores', $factores);
    }


    public function store(Request $request)
    {
        $input = $request->all();

        FactorVolumetrico::create($input);

        Flash::success('Factor Volumetrico guardado correctamente.');

        return redirect(route('factores-volumetricos.index'));

    }



    public function actualizar(Request $request)
    {
        $id = $request->id;
        $factor = FactorVolumetrico::find($id);

        if (empty($factor)) {
            Flash::error('Factor Volumetrico no encontrado.');

            return redirect(route('factores-volumetricos.index'));
        }

        $input = $request->all();
        $factor->update($input);
        Flash::success('Factor Volumetrico guardado correctamente.');

        return redirect(route('factores-volumetricos.index'));

    }
}
