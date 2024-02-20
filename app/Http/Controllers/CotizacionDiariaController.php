<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCotizacionRequest;
use App\Http\Requests\UpdateCotizacionRequest;
use App\Models\CotizacionDiaria;
use App\Models\Material;
use App\Patrones\Fachada;
use App\Repositories\CotizacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use DB;

class CotizacionDiariaController extends AppBaseController
{
    /** @var  CotizacionRepository */
    private $cotizacionRepository;

    public function __construct(CotizacionRepository $cotizacionRepo)
    {
        $this->cotizacionRepository = $cotizacionRepo;
    }

    /**
     * Display a listing of the CotizacionDiaria.
     *
     * @param Request $request
     *
     * @return Response
     */


    public function lista($id, Request $request)
    {
        $fecha = $request->fecha;
        if ($fecha) {
            $cotizacions = CotizacionDiaria::whereMineralId($id)->whereFecha($fecha)->orderByDesc('id')->paginate(50);
        } else {
            $cotizacions = CotizacionDiaria::whereMineralId($id)->orderByDesc('id')->paginate(50);
        }
        $mineral = Material::whereId($id)->first();
        return view('cotizacions.index')
            ->with('cotizacions', $cotizacions)->with('mineral', $mineral);
    }

    public function register($id)
    {
        if(date('w')==1){
            Flash::error("No se pueden ingresar cotizaciones del día lunes");
            return redirect(route('home'));
        }
        $mineral = Material::find((int)$id);
        return view('cotizacions.create', compact('id', 'mineral'));
    }


    public function createMultiple()
    {
        if(date('w')==1){
            Flash::error("No se pueden ingresar cotizaciones del día lunes");
            return redirect(route('home'));
        }
        $minerales = Material::whereConCotizacion(true)->orderBy('id')->get();
        return view('cotizacions.create_multiple')->with('minerales', $minerales);
    }

    public function storeMultiple(Request $request)
    {
        $fecha = Fachada::setFormatoFecha($request->fecha);
        if(Fachada::tieneCotizacionDiaria($fecha))
        {
            Flash::error("Ya se tiene cotizaciones diarias para esta fecha");
            return redirect(route('cotizacions.createMultiple'));
        }

        DB::beginTransaction();
        try {
            $input['fecha'] = $fecha;

            for ($i = 0; $i < count($request->monto); $i++) {
                $input['monto'] = $request->monto[$i];
                $input['mineral_id'] = $request->mineral_id[$i];
                $input['unidad'] = $request->unidad[$i];
                if ($this->esRegistrado($fecha, $request->unidad[$i], $request->mineral_id[$i])) {
                    Flash::error('La cotización del mineral ' . Material::find($request->mineral_id[$i])->nombre . ' ya fue registrada con la fecha ' . $request->fecha);
                    DB::rollBack();
                } else {
                    $this->cotizacionRepository->create($input);
                    Flash::success('Cotizacion del mineral ' . Material::find($request->mineral_id[$i])->nombre . ' guardada correctamente.');
                }
            }
//        Flash::success('Cotizaciones guardadas correctamente.');
            DB::commit();
            return redirect(route('materials.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->make_exception($e);
        }

    }

    public function store(CreateCotizacionRequest $request)
    {
        $input = $request->all();

        $fecha= Fachada::setFormatoFecha($request->fecha);
        $input['fecha'] =$fecha;

        if ($this->esRegistrado($fecha, $request->unidad, $request->mineral_id)) {
            Flash::error('La cotización del mineral ya fue registrada con la fecha ' . $request->fecha);
            return redirect(route('cotizacions.register', $request->mineral_id));
        }
        $cotizacion = $this->cotizacionRepository->create($input);

        Flash::success('Cotización guardada correctamente.');

        return redirect(route('cotizacions.lista', [$cotizacion->mineral_id]));
    }

    private function esRegistrado($fecha, $unidad, $mineral_id)
    {
        return CotizacionDiaria::whereFecha($fecha)->whereUnidad($unidad)->whereMineralId($mineral_id)->count() > 0;
    }

    /**
     * Show the form for editing the specified CotizacionDiaria.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cotizacion = $this->cotizacionRepository->find($id);

        if (empty($cotizacion)) {
            Flash::error('Cotización no encontrada');

            return redirect(route('cotizacions.index'));
        }
        return view('cotizacions.edit')->with('cotizacion', $cotizacion);
    }


    public function update($id, UpdateCotizacionRequest $request)
    {
        $dia= Fachada::setFormatoFecha($request->fecha);
        $dia = date( 'w', strtotime($dia));

        if($dia==1){
            Flash::error("No se pueden modificar cotizaciones del día lunes");
            return redirect(route('home'));
        }
        $cotizacion = $this->cotizacionRepository->find($id);

        if (empty($cotizacion)) {
            Flash::error('Cotización no encontrada');

            return redirect(route('materials.index'));
        }
        $input = $request->all();
        $input['fecha'] = Fachada::setFormatoFecha($request->fecha);

        $cotizacion = $this->cotizacionRepository->update($input, $id);

        Flash::success('Cotización modificada correctamente.');

        return redirect(route('cotizacions.lista', [$cotizacion->mineral_id]));
    }

}
