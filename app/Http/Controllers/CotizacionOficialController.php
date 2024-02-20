<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCotizacionOficialRequest;
use App\Http\Requests\UpdateCotizacionOficialRequest;
use App\Models\CotizacionDiaria;
use App\Models\CotizacionOficial;
use App\Models\Material;
use App\Patrones\Fachada;
use App\Repositories\CotizacionOficialRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CotizacionOficialController extends AppBaseController
{
    /** @var  CotizacionOficialRepository */
    private $cotizacionOficialRepository;

    public function __construct(CotizacionOficialRepository $cotizacionOficialRepo)
    {
        $this->cotizacionOficialRepository = $cotizacionOficialRepo;
    }

    public function index(Request $request)
    {
        $fecha = $request->fecha;

        if ($fecha) {
            $fechaInferior = $this->getFechaInferior($fecha);
            if ($fechaInferior) {
                $fechaSuperior = $this->getFechaSuperior($fechaInferior);

                $cotizacions = CotizacionOficial::whereMineralId($request->id)
                    ->whereEsAprobado(true)
                    ->whereBetween('fecha', [$fechaInferior, $fechaSuperior])
                    ->orderByDesc('id')
                    ->paginate(50);
            } else {
                $cotizacions = [];
            }
        } else {
            $cotizacions = CotizacionOficial::whereMineralId($request->id)->whereEsAprobado(true)->orderByDesc('id')->paginate(50);
        }
        $mineral = Material::whereId($request->id)->first();
        return view('cotizacion_oficials.index')->with('cotizacionOficials', $cotizacions)->with('mineral', $mineral);
    }

    private function getFechaInferior($fechaBusqueda)
    {
        $fechaInferior = null;
        $cotizacion = CotizacionOficial::orderByDesc('fecha')->whereEsAprobado(true)->where('fecha', '<=', $fechaBusqueda)->first();
        if (!is_null($cotizacion))
            $fechaInferior = $cotizacion->fecha;
        return $fechaInferior;
    }

    private function getFechaSuperior($fechaInferior)
    {
        $fecha = new \DateTime($fechaInferior);

        if ($fecha->format('d') > 15)
            return (date("t/m/Y", strtotime($fechaInferior)));
        else
            return (date("15/m/Y", strtotime($fechaInferior)));
    }

    public function create(Request $request)
    {
        $id = $request->id;
        $mineral = Material::find((int)$request->id);
        return view('cotizacion_oficials.create', compact('id', 'mineral'));
    }

    public function createMultiple()
    {
        $minerales = Material::whereConCotizacion(true)->get();
        return view('cotizacion_oficials.create_multiple')->with('minerales', $minerales);
    }

    public function storeMultiple(Request $request)
    {
        \DB::beginTransaction();
        try {

            $dia = date("d", strtotime(Fachada::setFormatoFecha($request->fecha)));
            if ($dia != 16 and $dia != 1) {
                Flash::error('La cotización del mineral debe ser registrada con día 1 o 16');
                return redirect(route('cotizacionOficials.createMultiple'));
            }

            //$fecha = Fachada::setDateFormat(Fachada::setDate($request->fecha));
            //dd(Fachada::setDate($request->fecha));
            if (Fachada::tieneCotizacionOficial(Fachada::setDate($request->fecha))) {
                Flash::error("Ya se tiene cotizaciones oficiales para la fecha " . $request->fecha);
                return redirect(route('cotizacionOficials.createMultiple'));
            }

            $res= $this->registrarDocumento($request, Fachada::setDate($request->fecha));
            if($res==false){
                Flash::error("Elija un archivo correcto" );
                return redirect(route('cotizacionOficials.createMultiple'));
            }


            $input['fecha'] = Fachada::setFormatoFecha($request->fecha);

            for ($i = 0; $i < count($request->monto); $i++) {
                $input['monto'] = $request->monto[$i];
                $input['mineral_id'] = $request->mineral_id[$i];
                $input['unidad'] = $request->unidad[$i];
                $input['alicuota_exportacion'] = $request->alicuota_exportacion[$i];
                $input['alicuota_interna'] = $request->alicuota_interna[$i];
                $this->cotizacionOficialRepository->create($input);
            }
            Flash::success('Cotizaciones guardadas correctamente.');
            \DB::commit();

            return redirect(route('materials.index'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function store(CreateCotizacionOficialRequest $request)
    {
        $input = $request->all();
        $dia = date("d", strtotime(Fachada::setFormatoFecha($request->fecha)));

        if ($dia != 16 and $dia != 1) {
            Flash::error('La cotización del mineral debe ser registrada con día 1 o 16');
            return redirect(route('cotizacionOficials.create', ['id' => $request->mineral_id]));
        }

        if ($this->esRegistrado($request->fecha, $request->unidad, $request->mineral_id)) {
            Flash::error('La cotización del mineral ya fue registrada en la fecha ' . $request->fecha);
            return redirect(route('cotizacionOficials.create', ['id' => $request->mineral_id]));
        }

        $input['fecha'] = Fachada::setFormatoFecha($request->fecha);
        $cotizacionOficial = $this->cotizacionOficialRepository->create($input);

        Flash::success('Cotización Oficial guardada correctamente.');
        return redirect(route('cotizacionOficials.index', ['id' => $cotizacionOficial->mineral_id]));
    }

    private function esRegistrado($fecha, $unidad, $mineral_id)
    {
        return CotizacionOficial::whereFecha($fecha)->whereEsAprobado(true)->whereUnidad($unidad)->whereMineralId($mineral_id)->count() > 0;
    }


    /**
     * Show the form for editing the specified CotizacionOficial.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cotizacionOficial = $this->cotizacionOficialRepository->find($id);
        if (empty($cotizacionOficial)) {
            Flash::error('Cotización Oficial no encontrado');

            return redirect(route('cotizacionOficials.index'));
        }

        return view('cotizacion_oficials.edit')->with('cotizacionOficial', $cotizacionOficial);
    }

    public function update($id, UpdateCotizacionOficialRequest $request)
    {
        $cotizacionOficial = $this->cotizacionOficialRepository->find($id);

        if (empty($cotizacionOficial)) {
            Flash::error('Cotización Oficial no encontrado');

            return redirect(route('cotizacionOficials.index'));
        }
        $input = $request->all();
        $input['fecha'] = Fachada::setFormatoFecha($request->fecha);

        $cotizacionOficial = $this->cotizacionOficialRepository->update($input, $id);

        Flash::success('Cotización Oficial actualizada correctamente.');

        return redirect(route('cotizacionOficials.index', ['id' => $cotizacionOficial->mineral_id]));
    }

    public function getDetalle($fecha)
    {
        $cotizaciones = CotizacionOficial::whereFecha($fecha)->whereEsAprobado(false)->get();

        if ($cotizaciones->count() == 0) {
            Flash::error('Cotizaciones Oficiales no encontradas');

            return redirect(route('home'));
        }

        return view('cotizacion_oficials.aprobar')->with('cotizaciones', $cotizaciones);
    }

    public function aprobar(Request $request)
    {
        $fecha = $request->fecha;

        CotizacionOficial::whereFecha($fecha)
            ->whereEsAprobado(false)
            ->update(['es_aprobado' => true]);

        Flash::success('Cotizaciones oficiales aprobadas');

        return redirect(route('home'));
    }

    private function registrarDocumento(Request $request, $fecha)
    {
        try {
            $files = $request->url_documento;
            if (is_null($files)) {
                return false;
            }

            //unir varios pdf's en uno
            $pdf = new \PDFMerger;
            foreach ($files as $key => $file) {
                $pdf->addPDF($file->getPathName(), 'all');
            }

            //juntando todos los documentos
            $nombreArchivo = 'oficial_'.date("Y-m-d", strtotime($fecha)).'.pdf';
            $pdf->merge('file', public_path() . "/documents/" . $nombreArchivo);

            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

}
