<?php

namespace App\Http\Controllers;
use App\Models\MovimientoCatalogo;
use Flash;
use Illuminate\Http\Request;

class MovimientoCatalagoController extends Controller
{

    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';
        $movimientoCatalogo=MovimientoCatalogo::where('descripcion', 'ilike', '%' . $txtBuscar . '%')->orderBy('descripcion')
        ->paginate(50);

        return view('movimiento_catalogo.index',compact('movimientoCatalogo'));
    }


    public function create()
    {
        return view('movimiento_catalogo.create');
    }



    public function store(Request $request)
    {
        $input = $request->all();
        //$input['es_lote'] = $request->has('es_lote');


        MovimientoCatalogo::create($input);

        Flash::success('Cuenta guardada correctamente.');
        return redirect(route('movimientos-catalogos.index'));
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $movimientoCatalogo = MovimientoCatalogo::find($id);
        return view('movimiento_catalogo.edit', compact('movimientoCatalogo'));
    }


    public function update(Request $request, $id)
    {
        $movimientoCatalogo=MovimientoCatalogo::find($id);
        $movimientoCatalogo->fill($request->all());
        $movimientoCatalogo->es_lote = $request->has('es_lote') ? true : false;
        $movimientoCatalogo->save();
        Flash::success('Cuenta modificada correctamente.');

        return redirect(route('movimientos-catalogos.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
