<?php

namespace App\Http\Controllers;

use App\Models\SatisfaccionCliente;
use App\Models\Ubicacion;
use App\Models\UbicacionFormulario;
use Illuminate\Http\Request;
use Response;
use DB;

class UbicacionController extends AppBaseController
{
    public function lista(Request $request)
    {
        $ubicaciones = DB::table('ubicacion')
            ->join('ubicacion_formulario', 'ubicacion.id', 'ubicacion_formulario.ubicacion_id')
            ->where('ubicacion_formulario.alta', true)
            ->where('ubicacion.es_oficina', true)
            ->select('ubicacion.id', 'ubicacion.descripcion')
            ->get();
        return $ubicaciones;
    }

    public function buscarUbicacion($id)
    {
        $cuadros = DB::table('ubicacion')
            ->join('ubicacion_formulario', 'ubicacion.id', 'ubicacion_formulario.ubicacion_id')
            ->whereAlta(true)
            ->where('ubicacion.es_oficina', true)
            ->whereFormularioLiquidacionId($id)
            ->select('ubicacion.descripcion')
            ->get();

        return $cuadros;
    }

    public function getLotes()
    {
        $cuadros = DB::table('formulario_liquidacion')
            ->join('ubicacion_formulario', 'formulario_liquidacion.id', 'ubicacion_formulario.formulario_liquidacion_id')
            ->join('ubicacion', 'ubicacion.id', 'ubicacion_formulario.ubicacion_id')
            ->whereAlta(true)
            ->where('ubicacion.es_oficina', true)
            ->select(DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3)) as lote"),
                'ubicacion_formulario.formulario_liquidacion_id')
            ->groupBy('ubicacion_formulario.formulario_liquidacion_id', "formulario_liquidacion.sigla", "formulario_liquidacion.numero_lote",
                "formulario_liquidacion.letra", "formulario_liquidacion.anio")
            ->get();

        return $cuadros;
    }


    public function getLotesDeCuadro($cuadro)
    {
        $lotes = DB::table('formulario_liquidacion')
            ->join('ubicacion_formulario', 'formulario_liquidacion.id', 'ubicacion_formulario.formulario_liquidacion_id')
            ->join('ubicacion', 'ubicacion.id', 'ubicacion_formulario.ubicacion_id')
            ->where('ubicacion_formulario.alta', true)
            ->where('ubicacion.descripcion', $cuadro)
            ->select(DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3)) as lote"),
                'ubicacion_formulario.formulario_liquidacion_id', 'ubicacion_formulario.id')
            ->orderBy('formulario_liquidacion.numero_lote')
            ->get();

        return $lotes;
    }

    public function agregar(Request $request)
    {
        $input = $request->all();
        $ubicacion = Ubicacion::whereDescripcion($request->descripcion)->first();
        $contador = UbicacionFormulario::whereFormularioLiquidacionId($request->formulario_liquidacion_id)->whereUbicacionId($ubicacion->id)->count();
        if ($contador > 0)
            return response()->json(['res' => false, 'message' => 'Ya se registró la ubicación del lote anteriormente']);

        $input['ubicacion_id'] = $ubicacion->id;

        UbicacionFormulario::create($input);
        return response()->json(['res' => true, 'message' => 'Ubicación registrada correctamente']);
    }

    public function mover(Request $request)
    {
        \DB::beginTransaction();
        try {
            $input = $request->all();
            $ubicacion = Ubicacion::whereDescripcion($request->descripcion)->first();

            UbicacionFormulario::whereFormularioLiquidacionId($request->formulario_liquidacion_id)->update(['alta' => false]);

            $input['ubicacion_id'] = $ubicacion->id;
            UbicacionFormulario::create($input);

            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Lote movido correctamente']);


        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['res' => false, 'message' => $e]);
        }
    }

}
