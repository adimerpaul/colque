<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDescuentoBonificacionRequest;
use App\Http\Requests\UpdateDescuentoBonificacionRequest;
use App\Models\Cooperativa;
use App\Models\DescuentoBonificacion;
use App\Models\FormularioDescuento;
use App\Patrones\EnFuncion;
use App\Patrones\UnidadDescuentoBonificacion;
use App\Repositories\DescuentoBonificacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class DescuentoController extends AppBaseController
{
    /** @var  DescuentoBonificacionRepository */
    private $descuentoRepository;

    public function __construct(DescuentoBonificacionRepository $descuentoRepo)
    {
        $this->descuentoRepository = $descuentoRepo;
    }

    /**
     * Display a listing of the Bonificacion.
     *
     * @param Request $request
     *
     * @return Response
     */

    public function lista($id, Request $request)
    {
        $tipo = $request->tipo;
        $alta = $request->alta;
        $txtBuscar = $request->txtBuscar;
        if (is_null($tipo))
            $tipo = '%';

        if (is_null($alta))
            $alta = true;

        if (is_null($txtBuscar))
            $txtBuscar = '';

        $descuentos = DescuentoBonificacion::where('nombre', 'ilike', '%' . $txtBuscar . '%')
            ->where('tipo', 'like', $tipo)
            ->where('alta', $alta)
            ->where('cooperativa_id', $id)
            ->orderBy('tipo')->orderBy('id')->paginate(30);

        $cooperativa = Cooperativa::whereId($id)->first();
        return view('descuentos.index')
            ->with('descuentos', $descuentos)->with('cooperativa', $cooperativa);
    }

    public function register($id)
    {
        return view('descuentos.create')->with('id', $id);
    }

    public function create()
    {
        return view('descuentos.create');
    }

    /**
     * Store a newly created Descuento in storage.
     *
     * @param CreateBonificacionRequest $request
     *
     * @return Response
     */
    public function store(CreateDescuentoBonificacionRequest $request)
    {
        $input = $request->all();
        if ($request->unidad == UnidadDescuentoBonificacion::Constante)
            $input['en_funcion'] = null;

        if($request->en_funcion == EnFuncion::Sacos)
            $input['unidad'] = UnidadDescuentoBonificacion::Cantidad;

        $descuento = $this->descuentoRepository->create($input);

        Flash::success($descuento->tipo . ' guardado correctamente.');

        return redirect(route('descuentosBonificaciones.lista', [$descuento->cooperativa_id]));

    }

    /**
     * Show the form for editing the specified Bonificacion.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $descuento = $this->descuentoRepository->find($id);

        if (empty($descuento)) {
            Flash::error('Descuento no encontrada');

            return redirect(route('descuentosBonificaciones.index'));
        }

        return view('descuentos.edit')->with('descuento', $descuento);
    }

    /**
     * Update the specified Descuento in storage.
     *
     * @param int $id
     * @param UpdateBonificacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDescuentoBonificacionRequest $request)
    {
        $descuento = $this->descuentoRepository->find($id);

        if (empty($descuento)) {
            Flash::error('Descuento no encontrada');

            return redirect(route('descuentosBonificaciones.index'));
        }
        $input = $request->all();
        if ($request->unidad == UnidadDescuentoBonificacion::Constante)
            $input['en_funcion'] = null;

        if($request->en_funcion == EnFuncion::Sacos)
            $input['unidad'] = UnidadDescuentoBonificacion::Cantidad;

        $descuento = $this->descuentoRepository->update($input, $id);

        Flash::success($descuento->tipo . '  modificado correctamente.');

        return redirect(route('descuentosBonificaciones.lista', [$descuento->cooperativa_id]));

    }

    public function cambiarEstado($id, $estado)
    {
        $descuento = DescuentoBonificacion::findOrFail($id);
        $descuento->update(array('alta' => $estado));
        $descuento->save();
        if ($estado) {
            Flash::success('Registro dado de alta correctamente.');
            return redirect(route('descuentosBonificaciones.lista', [$descuento->cooperativa_id]));

        } else {
            Flash::error('Registro dado de baja correctamente');
            return redirect(route('descuentosBonificaciones.lista', [$descuento->cooperativa_id]));

        }
    }


    public function editarValor(Request $request)
    {
        $descuento = FormularioDescuento::findOrFail($request->idDescuento);

//        if($descuento->descuentoBonificacion->nombre=="BONO PRODUCTOR"){
//            $valorAntiguo=$descuento->valor;
//        }
        $descuento->update(array('valor' => $request->valor));
        $descuento->save();
        Flash::success('Valor cambiado correctamente.');
        return redirect(route('formularioLiquidacions.edit', [$descuento->formulario_liquidacion_id]));
    }
}
