<?php

namespace App\Http\Controllers\Activo;
use App\Http\Controllers\Controller;
use App\Models\Activo\Tipo;
use Flash;
use Illuminate\Http\Request;
use function redirect;
use function view;

class TipoController extends Controller
{


    public function index(Request $request)
    {
            $tipos=Tipo::all();
            return view('activos.tipos.index', compact('tipos'));
    }


    public function create()
    {
        return view('activos.tipos.create');
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $input["numero"] =Tipo::max("numero")+1000;

        if (Tipo::where('id')->exists()) {
            Flash::error('Ya existe un Numero de codigo en otro registro');

            return redirect()
                ->route('tipos-activos.create');
        }

        Tipo::create($input);

        Flash::success('Tipo de Activo guardado correctamente.');
        return redirect(route('tipos-activos.index'));

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $tipo = Tipo::find($id);
        return view('activos.tipos.edit', compact('tipo'));
    }


    public function update(Request $request, $id)
    {
        $tipo=Tipo::find($id);
        $tipo->fill($request->all());
        $tipo->save();
        Flash::success('Tipo de activo modificado correctamente.');

        return redirect(route('tipos-activos.index'));
    }


    public function destroy($id)
    {
        //
    }
}
