<?php

namespace App\Http\Controllers;

use App\Models\Anulado;
use Illuminate\Http\Request;

class AnuladoController extends Controller
{
    public function registerAnulado(Request $request)
    {
        $numeroFactura = $request['numeroFactura'];
        $periodo = $request['periodo'];
        $cuenta = $request['cuenta'];
        $monto = $request['monto'];
        $fechaEmision = $request['fechaEmision'];
        $cuf = $request['cuf'];

        Anulado::create([
            'numero_factura' => $numeroFactura,
            'periodo' => $periodo,
            'cuenta' => $cuenta,
            'monto' => $monto,
            'fecha_emision' => $fechaEmision,
            'cuf' => $cuf
        ]);

        return response()->json(["success" => true, "message" => $numeroFactura]);
    }
    public function anuladosGet(Request $request)
    {
        $fechaDesde = $request['fechaDesde'];
        $fechaHasta = $request['fechaHasta'];
        $anulados=Anulado::whereDate('created_at','>=',$fechaDesde)->whereDate('created_at','<=',$fechaHasta)->orderBy('created_at','desc')->get();
        return response()->json($anulados);
    }
}
