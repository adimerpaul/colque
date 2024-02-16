<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLaboratorioQuimicoRequest;
use App\Http\Requests\UpdateLaboratorioQuimicoRequest;
use App\Models\LaboratorioPrecio;
use App\Models\LaboratorioQuimico;
use App\Models\Producto;
use App\Repositories\LaboratorioQuimicoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LaboratorioQuimicoController extends AppBaseController
{
    /** @var  LaboratorioQuimicoRepository */
    private $laboratorioQuimicoRepository;

    public function __construct(LaboratorioQuimicoRepository $laboratorioQuimico)
    {
        $this->laboratorioQuimicoRepository = $laboratorioQuimico;
    }

    /**
     * Display a listing of the LaboratorioQuimico.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $laboratorios = LaboratorioQuimico::orderBy('nombre')->paginate();

        return view('laboratorio_quimicos.index')
            ->with('laboratorios', $laboratorios);
    }

    /**
     * Show the form for creating a new LaboratorioQuimico.
     *
     * @return Response
     */
    public function create()
    {
        return view('laboratorio_quimicos.create');
    }

    /**
     * Store a newly created LaboratorioQuimico in storage.
     *
     * @param CreateLaboratorioQuimicoRequest $request
     *
     * @return Response
     */
    public function store(CreateLaboratorioQuimicoRequest $request)
    {
        $input = $request->all();
        $laboratorioQuimico = $this->laboratorioQuimicoRepository->create($input);

        $productos = Producto::get();
        for ($i=0; $i < $productos->count(); $i++){
            $valores['producto_id'] = $productos[$i]->id;
            $valores['monto'] = 0;
            $valores['laboratorio_quimico_id'] = $laboratorioQuimico->id;
            LaboratorioPrecio::create($valores);
        }

        Flash::success('Laboratorio químico guardado correctamente.');
        return redirect(route('laboratorioQuimicos.index'));
    }

    /**
     * Show the form for editing the specified LaboratorioQuimico.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $laboratorio = $this->laboratorioQuimicoRepository->find($id);

        if (empty($laboratorio)) {
            Flash::error('Laboratorio no encontrado');

            return redirect(route('laboratorio_quimicos.index'));
        }

        return view('laboratorio_quimicos.edit')->with('laboratorio', $laboratorio);
    }

    /**
     * Update the specified LaboratorioQuimico in storage.
     *
     * @param int $id
     * @param UpdateLaboratorioQuimicoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLaboratorioQuimicoRequest $request)
    {
        $laboratorioQuimico = $this->laboratorioQuimicoRepository->find($id);

        if (empty($laboratorioQuimico)) {
            Flash::error('Tipo Reporte no encontrado');

            return redirect(route('laboratorio_quimicos.index'));
        }

        $laboratorioQuimico = $this->laboratorioQuimicoRepository->update($request->all(), $id);

        Flash::success('Laboratorio químico modificado correctamente.');

        return redirect(route('laboratorioQuimicos.index'));
    }

    public function getLaboratorios()
    {
        $laboratorioQuimicos = LaboratorioQuimico::orderBy('nombre')->get()->pluck("nombre", "id");
        return json_encode($laboratorioQuimicos);
    }
}
