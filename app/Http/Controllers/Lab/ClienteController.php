<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Lab\CreateClienteRequest;
use App\Models\Lab\Cliente;
use Illuminate\Http\Request;
use Flash;
use DB;

class ClienteController extends AppBaseController
{
    public function index(Request $request){
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $clientes = Cliente::
        where(function ($q) use ($txtBuscar) {
                $q->where('nombre', 'ilike', '%' . $txtBuscar . '%')
                    ->orWhere('nit', 'ilike', '%' . $txtBuscar . '%')
                    ->orWhere('celular', 'ilike', '%' . $txtBuscar . '%');
            })
            ->orderBy('nombre')->paginate(100);
        return view('lab.clientes.index')
            ->with('clientes', $clientes);
    }

    public function getCliente(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $cliente = Cliente::
        where(DB::raw('UPPER(nombre)'), strtoupper($txtBuscar))
            ->orWhere('nit', $txtBuscar)
            ->first();
        return response()->json(['res' => true, 'data' => $cliente]);
    }

    public function create()
    {
        return view('lab.clientes.create');
    }

    public function store(CreateClienteRequest $request)
    {
        $input = $request->all();

        $clienteCi = Cliente::whereNit($request->nit)->whereComplemento($request->complemento)->count();

        if ($clienteCi>0) {
            Flash::error('Ya existe un nit con otro registro');
            return redirect()->route('clientes-lab.create');
        }
        Cliente::create($input);

        if ($request->esModal) {
            return response()->json(['res' => true, 'message' => 'Cliente guardado correctamente.']);

        } else {

            Flash::success('Cliente guardado correctamente.');

            return redirect(route('clientes-lab.index'));

        }

    }

    public function getClientes()
    {
        $clientes = Cliente::orderBy('nombre')->get()->pluck('info_cliente', 'id');
        return json_encode($clientes);
    }

    public function edit($id)
    {
        $cliente = Cliente::find($id);

        if (empty($cliente)) {
            Flash::error('Cliente no encontrado');

            return redirect(route('clientes-lab.index'));
        }

        return view('lab.clientes.edit')->with('cliente', $cliente);
    }

    /**
     * Update the specified Cliente in storage.
     *
     * @param int $id
     * @param UpdateClienteRequest $request
     *
     * @return Response
     */
    public function update($id, CreateClienteRequest $request)
    {
        $cliente = Cliente::find($id);

        if (empty($cliente)) {
            Flash::error('Cliente no encontrado');

            return redirect(route('clientes-lab.index'));
        }

        $clienteCi = Cliente::whereNit($request->nit)->whereComplemento($request->complemento)->where('id', '<>', $cliente->id)->count();

        if ($clienteCi>0) {
            Flash::error('Ya existe un carnet con otro registro');

            return redirect()
                ->route('clientes-lab.edit', ['clientes_lab' => $cliente->id]);
        }

        $input = $request->all();
        $cliente->update($input);

        Flash::success('Cliente modificado correctamente.');

        return redirect(route('clientes-lab.index'));

    }


    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (empty($cliente)) {
            Flash::error('Cliente no encontrado');

            return redirect(route('clientes-lab.index'));
        }
//
//        if (!$cliente->puede_eliminarse) {
//            Flash::error('No es posible realizar esta acción');
//
//            return redirect(route('clientes.lista', [$cliente->cooperativa_id]));
//        }

        Cliente::destroy($id);

        Flash::success('Cliente eliminado correctamente.');

        return redirect(route('clientes-lab.index'));
    }
}
