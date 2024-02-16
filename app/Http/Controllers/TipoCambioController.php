<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoCambioRequest;
use App\Http\Requests\UpdateTipoCambioRequest;
use App\Models\TipoCambio;
use App\Patrones\Fachada;
use App\Repositories\TipoCambioRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TipoCambioController extends AppBaseController
{
    /** @var  TipoCambioRepository */
    private $tipoCambioRepository;

    public function __construct(TipoCambioRepository $tipoCambioRepo)
    {
        $this->tipoCambioRepository = $tipoCambioRepo;
    }

    /**
     * Display a listing of the TipoCambio.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $fecha=$request->fecha;
        if($fecha) {
            $tipoCambios = TipoCambio::whereFecha($fecha)->orderByDesc('id')->paginate(50);
        }
        else{
            $tipoCambios = TipoCambio::orderByDesc('id')->paginate(50);
        }
        return view('tipo_cambios.index')
            ->with('tipoCambios', $tipoCambios);
    }

    /**
     * Show the form for creating a new TipoCambio.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipo_cambios.create');
    }

    /**
     * Store a newly created TipoCambio in storage.
     *
     * @param CreateTipoCambioRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoCambioRequest $request)
    {
        $input = $request->all();
        $fecha = Fachada::setFormatoFecha($request->fecha);
        $input['fecha'] = $fecha;
        $input['api'] = false;
        $contador = TipoCambio::whereFecha($fecha)->count();
        if ($contador>0) {
            Flash::error('El tipo de cambio ya fue registrado con la fecha '. $request->fecha);

            return redirect(route('tipoCambios.create'));
        }

        $tipoCambio = $this->tipoCambioRepository->create($input);

        Flash::success('Tipo Cambio guardado correctamente.');

        return redirect(route('tipoCambios.index'));
    }




    /**
     * Show the form for editing the specified TipoCambio.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoCambio = $this->tipoCambioRepository->find($id);

        if (empty($tipoCambio)) {
            Flash::error('Tipo Cambio no encontrado');

            return redirect(route('tipoCambios.index'));
        }

        return view('tipo_cambios.edit')->with('tipoCambio', $tipoCambio);
    }

    /**
     * Update the specified TipoCambio in storage.
     *
     * @param int $id
     * @param UpdateTipoCambioRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoCambioRequest $request)
    {
        $tipoCambio = $this->tipoCambioRepository->find($id);

        if (empty($tipoCambio)) {
            Flash::error('Tipo Cambio no encontrado');

            return redirect(route('tipoCambios.index'));
        }

        $input = $request->all();
        $input['fecha'] = Fachada::setFormatoFecha($request->fecha);
        $tipoCambio = $this->tipoCambioRepository->update($input, $id);

        Flash::success('Tipo Cambio modificado correctamente.');

        return redirect(route('tipoCambios.index'));
    }

}
