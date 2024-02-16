<?php

namespace App\Http\Controllers;

use App\Models\Costo;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CostoController extends AppBaseController
{


    /**
     * Display a listing of the Costo.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getCosto($id)
    {
        $costo= Costo::whereFormularioLiquidacionId($id)
            ->first();
        return $costo;
    }

    public function actualizarLaboratorio($tipo, $id, $monto)
    {

        $costo = Costo::whereFormularioLiquidacionId($id)->first();
        if (empty($costo)) {
            Flash::error('Costo de laboratorio no encontrado');

            return redirect(route('formularioLiquidacions.index'));
        }
        if($tipo =='laboratorio')
            $costo->update(array('laboratorio' => $monto));
        else
            $costo->update(array('dirimicion' => $monto));
        $costo->save();

        return response()->json(['res' => true, 'message' => 'Monto guardado correctamente.']);
    }

}
