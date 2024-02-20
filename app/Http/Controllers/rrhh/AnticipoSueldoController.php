<?php

namespace App\Http\Controllers\Rrhh;

use App\Http\Controllers\Controller;
use App\Models\Rrhh\AnticipoSueldo;
use App\Models\Personal;
use Illuminate\Http\Request;
use function redirect;
use function view;
use Flash;

class AnticipoSueldoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $anticipos=AnticipoSueldo::wherePersonalId(auth()->user()->personal->id)->get();

        return view('rrhh.anticipos.index',compact('anticipos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $input=$request->all();
        $input["personal_id"] = auth()->user()->personal_id;
        $input["tipo"] ="Egreso";
        $input["es_cancelado"] = false;
        $input["es_aprobado"] = false;
        AnticipoSueldo::create($input);
        Flash::success('Anticipo solicitado correctamente.');
        return redirect()->route('anticipos-sueldos.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rrhh\AnticipoSueldo  $anticipoSueldo
     * @return \Illuminate\Http\Response
     */
    public function show(AnticipoSueldo $anticipoSueldo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rrhh\AnticipoSueldo  $anticipoSueldo
     * @return \Illuminate\Http\Response
     */
    public function edit(AnticipoSueldo $anticipoSueldo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rrhh\AnticipoSueldo  $anticipoSueldo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnticipoSueldo $anticipoSueldo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rrhh\AnticipoSueldo  $anticipoSueldo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $anticipos=AnticipoSueldo::find($id);
        if($anticipos->es_cancelado==false){
        $anticipos->delete();
        Flash::success('El anticipo fue eliminado');
        return redirect()
            ->route('anticipos-sueldos.index');}
        else{
            Flash::error('El anticipo ya fue cancelado, no se puede eliminar');
            return redirect()
            ->route('anticipos-sueldos.index');
        }    

    }
}
