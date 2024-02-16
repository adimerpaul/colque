<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoReporteRequest;
use App\Http\Requests\UpdateTipoReporteRequest;
use App\Models\CampoReporte;
use App\Models\TipoReporte;
use App\Patrones\Fachada;
use App\Repositories\TipoReporteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TipoReporteController extends AppBaseController
{
    /** @var  TipoReporteRepository */
    private $tipoReporteRepository;

    public function __construct(TipoReporteRepository $tipoReporte)
    {
        $this->tipoReporteRepository = $tipoReporte;
    }

    /**
     * Display a listing of the TipoReporte.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tipoReportes = TipoReporte::orderBy('nombre')->paginate();

        return view('tipo_reportes.index')
            ->with('tipoReportes', $tipoReportes);
    }

    /**
     * Show the form for creating a new TipoReporte.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipo_reportes.create');
    }

    /**
     * Store a newly created TipoReporte in storage.
     *
     * @param CreateTipoReporteRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoReporteRequest $request)
    {
        $input = $request->all();

        $tipoReporte = $this->tipoReporteRepository->create($input);
        $valor['tipo_reporte_id'] = $tipoReporte->id;
        for ($i=0; $i <count(Fachada::getCamposReporte()); $i++){
            $valor['nombre'] = Fachada::getCamposReporte()[$i];
            $valor['codigo'] = Fachada::getCodigosCamposReporte()[$i];
            $valor['visible'] = (Fachada::getCodigosCamposReporte()[$i]=='otros') ? false: true;

            CampoReporte::create($valor);
        }

        Flash::success('Tipo reporte guardado correctamente.');
        return redirect(route('tipoReportes.index'));
    }

    /**
     * Show the form for editing the specified TipoReporte.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoReporte = $this->tipoReporteRepository->find($id);

        if (empty($tipoReporte)) {
            Flash::error('Tipo Reporte no encontrado');

            return redirect(route('tipo_reportes.index'));
        }

        return view('tipo_reportes.edit')->with('tipoReporte', $tipoReporte);
    }

    /**
     * Update the specified TipoReporte in storage.
     *
     * @param int $id
     * @param UpdateTipoReporteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoReporteRequest $request)
    {
        $tipoReporte = $this->tipoReporteRepository->find($id);

        if (empty($tipoReporte)) {
            Flash::error('Tipo Reporte no encontrado');

            return redirect(route('tipo_reportes.index'));
        }

        $tipoReporte = $this->tipoReporteRepository->update($request->all(), $id);

        Flash::success('Tipo Reporte modificado correctamente.');

        return redirect(route('tipoReportes.index'));
    }

    public function getTipoReportes()
    {
        $tiposReportes = TipoReporte::orderBy('nombre')->get()->pluck("info", "id");
        return json_encode($tiposReportes);
    }
}
