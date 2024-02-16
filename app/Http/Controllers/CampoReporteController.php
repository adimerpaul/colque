<?php

namespace App\Http\Controllers;

//use App\Http\Requests\UpdateCampoReporteRequest;
use App\Models\CampoReporte;
use App\Models\TipoReporte;
use App\Patrones\Rol;
use App\Repositories\CampoReporteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CampoReporteController extends AppBaseController
{
    /** @var  CampoReporteRepository */
    private $campoReporteRepository;

    public function __construct(CampoReporteRepository $vehiculoRepo)
    {
        $this->campoReporteRepository = $vehiculoRepo;
    }

    public function index(Request $request)
    {
        $tipo_reporte_id=$request->tipo_reporte_id;
        $campos = CampoReporte::whereTipoReporteId($tipo_reporte_id)
            ->orderBy('nombre')->get();


        return $campos;
    }

    /**
     * Display a listing of the CampoReporte.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function edit($tipoId)
    {
        if($tipoId==1 and (auth()->user()->rol!=Rol::Administrador or auth()->user()->rol!=Rol::SuperAdmin))
            abort(403);
        $campos = CampoReporte::whereTipoReporteId($tipoId)
            ->orderBy('nombre')->get();

            $tipo = TipoReporte::find($tipoId);

        return view('campo_reportes.edit')
            ->with('campos', $campos)->with('tipo', $tipo);
    }

    /**
     * Update the specified CampoReporte in storage.
     *
     * @param int $id
     * @param UpdateCampoReporteRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $campo = $this->campoReporteRepository->find($id);

        if (empty($campo)) {
            Flash::error('CampoReporte no encontrado');

            return redirect(route('tipo_reportes.index'));
        }


            $campo->visible = !$campo->visible;
            $campo->save();

        return response()->json(['res' => true, 'message' => 'Campo guardado correctamente.']);

    }
}
