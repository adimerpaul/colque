<?php

namespace App\Http\Controllers;

use App\Models\PruebaMineral;
use Illuminate\Http\Request;

class PruebaMineralController
{
    public function getMinerales(Request $request)
    {
        $materials = PruebaMineral::orderBy('id')->get();

        return  $materials;
    }

    public function registrar(Request $request)
    {

        $input = $request->all();
        $input['ip_registro']=$request->ip();
        $material = PruebaMineral::create($input);

        return response()->json(['res' => true, 'message' => 'Mineral registrado correctamente']);
    }

    public function eliminar($id)
    {
        $min=PruebaMineral::find($id);
        if(empty($min))
            return response()->json(['res' => false, 'message' => 'No se encontró el mineral']);

        $min->delete();

        return response()->json(['res' => true, 'message' => 'Mineral eliminado correctamente']);
    }

}
