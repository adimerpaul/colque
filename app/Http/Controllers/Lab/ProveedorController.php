<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Lab\CreateProveedorRequest;
use App\Http\Requests\Lab\UpdateProveedorRequest;
use App\Models\Lab\Proveedor;
use Illuminate\Http\Request;
use Flash;
use DB;

class ProveedorController extends AppBaseController
{
    public function index(Request $request){
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $proveedores = Proveedor::
        where(function ($q) use ($txtBuscar) {
            $q->where('nombre', 'ilike', '%' . $txtBuscar . '%')
                ->orWhere('nit', 'ilike', '%' . $txtBuscar . '%');
        })
            ->orderBy('nombre')->paginate(100);
        return view('lab.proveedores.index')
            ->with('proveedores', $proveedores);
    }

    public function getProveedor(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $proveedor = Proveedor::
        where(DB::raw('UPPER(nombre)'), strtoupper($txtBuscar))
            ->orWhere('nit', $txtBuscar)
            ->first();
        return response()->json(['res' => true, 'data' => $proveedor]);
    }

    public function create()
    {
        return view('lab.proveedores.create');
    }

    public function store(CreateProveedorRequest $request)
    {
        $input = $request->all();

        $proveedorCi = Proveedor::whereNit($request->nit)->count();

        if ($proveedorCi>0) {
            Flash::error('Ya existe un nit con otro registro');
            return redirect()->route('proveedores-lab.create');
        }

        Proveedor::create($input);

        if ($request->esModal) {
            return response()->json(['res' => true, 'message' => 'Proveedor guardado correctamente.']);
        } else {
            Flash::success('Proveedor guardado correctamente.');
            return redirect(route('proveedores-lab.index'));
        }
    }

    public function getProveedores()
    {
        $proveedores = Proveedor::orderBy('nombre')->get()->pluck('info', 'id');
        return json_encode($proveedores);
    }

    public function edit($id)
    {
        $proveedor = Proveedor::find($id);

        if (empty($proveedor)) {
            Flash::error('Proveedor no encontrado');

            return redirect(route('proveedores-lab.index'));
        }

        return view('lab.proveedores.edit')->with('proveedor', $proveedor);
    }

    /**
     * Update the specified Proveedor in storage.
     *
     * @param int $id
     * @param UpdateProveedorRequest $request
     *
     * @return Response
     */
    public function update($id, CreateProveedorRequest $request)
    {
        $proveedor = Proveedor::find($id);

        if (empty($proveedor)) {
            Flash::error('Proveedor no encontrado');

            return redirect(route('proveedores-lab.index'));
        }

        $proveedorCi = Proveedor::whereNit($request->nit)->where('id', '<>', $proveedor->id)->count();

        if ($proveedorCi>0) {
            Flash::error('Ya existe un nit con otro registro');

            return redirect()
                ->route('proveedores-lab.edit', ['proveedores_lab' => $id]);
        }

        $input = $request->all();
        $proveedor->update($input);

        Flash::success('Proveedor modificado correctamente.');

        return redirect(route('proveedores-lab.index'));

    }


    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);

        if (empty($proveedor)) {
            Flash::error('Proveedor no encontrado');

            return redirect(route('proveedores-lab.index'));
        }
//
//        if (!$proveedor->puede_eliminarse) {
//            Flash::error('No es posible realizar esta acción');
//
//            return redirect(route('proveedores.lista', [$proveedor->cooperativa_id]));
//        }

        Proveedor::destroy($id);

        Flash::success('Proveedor eliminado correctamente.');

        return redirect(route('proveedores-lab.index'));
    }
}
