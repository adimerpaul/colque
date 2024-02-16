<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Http\Requests\CreateAnticipoRequest;
use App\Models\Anticipo;
use App\Http\Controllers\AppBaseController;
use App\Models\BasePlomoPlata;
use App\Models\Cliente;
use App\Models\FormularioLiquidacion;
use App\Models\PagoMovimiento;
use Illuminate\Http\Request;
use Flash;
use Response;
use Luecano\NumeroALetras\NumeroALetras;

class BasePlomoPlataController extends AppBaseController
{

    public function index(Request $request)
    {
        return BasePlomoPlata::orderBy('lme_pb_minimo')->get();
    }

    public function store(Request $request)
    {
        $input = $request->all();
        BasePlomoPlata::create($input);

        return response()->json(['res' => true, 'message' => 'Registro guardardo correctamente']);
    }

    public function destroy($id)
    {
        $base = BasePlomoPlata::find($id);
        if (empty($base)) {
            return response()->json(['res' => false, 'message' => 'Registro no encontrado']);
        }
        $base->delete($id);

        return response()->json(['res' => true, 'message' => 'Registro eliminado correctamente']);
    }


}
