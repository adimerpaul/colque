<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProveedorRequest;
use App\Http\Requests\UpdateProveedorRequest;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Repositories\ProveedorRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ProveedorController extends AppBaseController
{
    /** @var  ProveedorRepository */
    private $proveedorRepository;

    public function __construct(ProveedorRepository $proveedorRepo)
    {
        $this->proveedorRepository = $proveedorRepo;
    }

    /**
     * Display a listing of the Proveedor.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $proveedores = Proveedor::
            whereEsAprobado(true)
            ->where(function ($q) use ($txtBuscar) {
                $q->where('nombre', 'ilike', '%' . $txtBuscar . '%')
                    ->orWhere('nit', 'ilike', '%' . $txtBuscar . '%');
            })
            ->orderBy('nombre')->paginate();

        return view('proveedores.index')
            ->with('proveedores', $proveedores);
    }

    /**
     * Show the form for creating a new Proveedor.
     *
     * @return Response
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created Proveedor in storage.
     *
     * @param CreateProveedorRequest $request
     *
     * @return Response
     */
    public function store(CreateProveedorRequest $request)
    {
        $input = $request->all();
// MOMENTANEO
        $input['es_aprobado'] = true;
        $proveedor = $this->proveedorRepository->create($input);

        if ($request->esFormulario) {
            return response()->json(['res' => true, 'message' => 'Proveedor guardado correctamente.']);
        } else {

            Flash::success('Proveedor guardado correctamente.');

            return redirect(route('proveedores.index'));
        }
    }

    /**
     * Show the form for editing the specified Proveedor.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $proveedor = $this->proveedorRepository->find($id);

        if (empty($proveedor)) {
            Flash::error('Proveedor no encontrado');

            return redirect(route('proveedores.index'));
        }

        return view('proveedores.edit')->with('proveedor', $proveedor);
    }

    /**
     * Update the specified Proveedor in storage.
     *
     * @param int $id
     * @param UpdateProveedorRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProveedorRequest $request)
    {
        $proveedor = $this->proveedorRepository->find($id);

        if (empty($proveedor)) {
            Flash::error('Proveedor no encontrado');

            return redirect(route('proveedores.index'));
        }

        $busquedaProveedor = Proveedor::whereNit($request->nit)->where('id', '<>', $id)->count();
        if ($busquedaProveedor > 0) {
            Flash::error('Ya existe un proveedor con el nit ingresado.');
            return redirect(route('proveedores.index'));
        }

        $proveedor = $this->proveedorRepository->update($request->all(), $id);

//        $proveedor= Proveedor::whereId(210)->update(['nombre' => 'ANGEL CRUZ CHIRI.']);
//        if($proveedor)
//            dd('si');
//        else
//            dd('no');

        Flash::success('Proveedor modificado correctamente.');

        return redirect(route('proveedores.index'));
    }

    public function getProveedores()
    {
        $proveedores = Proveedor::whereEsAprobado(true)->orderBy('nombre')->get()->pluck('info', 'id');
        return json_encode($proveedores);
    }

}
