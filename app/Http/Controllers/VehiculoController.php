<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Models\Vehiculo;
use App\Repositories\VehiculoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class VehiculoController extends AppBaseController
{
    /** @var  VehiculoRepository */
    private $vehiculoRepository;

    public function __construct(VehiculoRepository $vehiculoRepo)
    {
        $this->vehiculoRepository = $vehiculoRepo;
    }

    /**
     * Display a listing of the Vehiculo.
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

        $vehiculos = Vehiculo::where('placa', 'ilike', '%' . $txtBuscar . '%')
            ->orderBy('placa')->paginate();

        return view('vehiculos.index')
            ->with('vehiculos', $vehiculos);
    }

    /**
     * Show the form for creating a new Vehiculo.
     *
     * @return Response
     */
    public function create()
    {
        return view('vehiculos.create');
    }

    /**
     * Store a newly created Vehiculo in storage.
     *
     * @param CreateVehiculoRequest $request
     *
     * @return Response
     */
    public function store(CreateVehiculoRequest $request)
    {
        $input = $request->all();

        $vehiculo = $this->vehiculoRepository->create($input);

        if ($request->esFormulario) {
            return response()->json(['res' => true, 'message' => 'Vehículo guardado correctamente.']);
        } else {
            Flash::success('Vehículo guardado correctamente.');
            return redirect(route('vehiculos.index'));
        }
    }

    /**
     * Show the form for editing the specified Vehiculo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $vehiculo = $this->vehiculoRepository->find($id);

        if (empty($vehiculo)) {
            Flash::error('Vehiculo no encontrado');

            return redirect(route('vehiculos.index'));
        }

        return view('vehiculos.edit')->with('vehiculo', $vehiculo);
    }

    /**
     * Update the specified Vehiculo in storage.
     *
     * @param int $id
     * @param UpdateVehiculoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVehiculoRequest $request)
    {
        $vehiculo = $this->vehiculoRepository->find($id);

        if (empty($vehiculo)) {
            Flash::error('Vehiculo no encontrado');

            return redirect(route('vehiculos.index'));
        }

        $vehiculo = $this->vehiculoRepository->update($request->all(), $id);

        Flash::success('Vehiculo modificado correctamente.');

        return redirect(route('vehiculos.index'));
    }

    public function getVehiculos()
    {
        $vehiculos = Vehiculo::orderBy('placa')->get()->pluck("info", "id");
        return json_encode($vehiculos);
    }

    public function getVehiculosAndroid()
    {
        $vehiculos = Vehiculo::orderBy('placa')->select('id', 'placa', 'marca')->get();
        return ($vehiculos);
    }
}
