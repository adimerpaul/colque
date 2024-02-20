<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Lab\RangoMedicion;
use App\Models\Lab\TemperaturaHumedad;
use Illuminate\Http\Request;
use Flash;
use DB;

class TemperaturaHumedadController extends AppBaseController
{
    public function index(Request $request)
    {
        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fecha_fin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $ambiente=$request->ambiente;

        $temperaturas = TemperaturaHumedad::
            whereAmbiente($ambiente)
            ->whereBetween('created_at', [$fecha_inicial, $fecha_fin])
            ->orderBy('created_at')->paginate(100);
        return view('lab.temperaturas_humedades.index')
            ->with('temperaturas', $temperaturas)->with('ambiente', $ambiente)
            ->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $input = $request->all();
            $rango= RangoMedicion::whereTipo("Temperatura")->orderByDesc('id')->first();
            if(empty( $rango)){
                \DB::rollBack();
                Flash::error('Registre primero los rangos');
                return redirect(url('lab/temperaturas-humedades?ambiente='.$request->ambiente));
            }
            $input["rango_medicion_id"]= $rango->id;
            $input["valor"]= $request->temperatura;
            $input["tipo"]= "Temperatura";
            TemperaturaHumedad::create($input);

            $rango= RangoMedicion::whereTipo("Humedad")->orderByDesc('id')->first();
            if(empty( $rango)){
                \DB::rollBack();
                Flash::error('Registre primero los rangos');
                return redirect(url('lab/temperaturas-humedades?ambiente='.$request->ambiente));
            }
            $input["rango_medicion_id"]= $rango->id;
            $input["valor"]= $request->humedad;
            $input["tipo"]= "Humedad";
            TemperaturaHumedad::create($input);

            \DB::commit();
            Flash::success('Registro guardado correctamente.');
            return redirect(url('lab/temperaturas-humedades?ambiente='.$request->ambiente));
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }

    }

    public function actualizar(Request $request)
    {
        $id = $request->id;
        $temperatura = TemperaturaHumedad::find($id);

        if (empty($temperatura)) {
            Flash::error('Registro no encontrado.');

            return redirect(url('lab/temperaturas-humedades?ambiente='.$temperatura->ambiente));
        }

        $input = $request->all();
        $temperatura->update($input);
        Flash::success('Registro guardado correctamente.');

        return redirect(url('lab/temperaturas-humedades?ambiente='.$temperatura->ambiente));

    }


}
