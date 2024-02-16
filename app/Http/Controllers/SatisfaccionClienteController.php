<?php

namespace App\Http\Controllers;

use App\Models\SatisfaccionCliente;
use Illuminate\Http\Request;
use Response;

class SatisfaccionClienteController extends AppBaseController
{
    public function index(Request $request)
    {
        $satisfacciones = SatisfaccionCliente::orderByDesc('id')->get();
        return $satisfacciones;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if (isset($request->id))
        {
            $contador = SatisfaccionCliente::whereFormularioLiquidacionId($request->id)->count();

            if ($contador > 0)
                return response()->json(['res' => false, 'message' => 'Ya se registró la evaluación del lote anteriormente']);
        }

        $input['formulario_liquidacion_id'] = $request->id;
        $input['ip'] = $request->ip();
        SatisfaccionCliente::create($input);
        return response()->json(['res' => true, 'message' => 'Respuesta registrada correctamente']);
    }
}
