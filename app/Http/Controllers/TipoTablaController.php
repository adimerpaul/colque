<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoTablaRequest;
use App\Http\Requests\UpdateTipoTablaRequest;
use App\Models\TipoTabla;
use App\Repositories\TipoTablaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TipoTablaController extends AppBaseController
{
    /** @var  TipoTablaRepository */
    private $tipoRepository;

    public function __construct(TipoTablaRepository $tipoRepo)
    {
        $this->tipoRepository = $tipoRepo;
    }

    /**
     * Display a listing of the Chofer.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tabla=$request->tabla;
        $tipos = TipoTabla::where(function ($query) use ($tabla) {
                if ($tabla!=null)
                    $query->where('tabla', $tabla);
                else
                    $query->where('id', '>', '0');
            })
            ->orderBy('tabla')->orderByDesc('id')->paginate();

        return view('tipos.index')
            ->with('tipos', $tipos);
    }

    /**
     * Show the form for creating a new Chofer.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipos.create');
    }

    /**
     * Store a newly created Chofer in storage.
     *
     * @param CreateTipoTablaRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoTablaRequest $request)
    {
        $input = $request->all();

        $tipo = $this->tipoRepository->create($input);

        return response()->json(['res' => true, 'message' => $tipo->tabla. ' guardado correctamente.']);

    }

    /**
     * Show the form for editing the specified Chofer.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipo = $this->tipoRepository->find($id);

        if (empty($tipo)) {
            Flash::error('No encontrado');

            return redirect(route('tipos.index'));
        }

        return view('tipos.edit')->with('tipo', $tipo);
    }

    /**
     * Update the specified Chofer in storage.
     *
     * @param int $id
     * @param UpdateTipoTablaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoTablaRequest $request)
    {
        $tipo = $this->tipoRepository->find($id);

        if (empty($tipo)) {
            Flash::error('No encontrado');

            return redirect(route('tipos.index'));
        }

        $tipo = $this->tipoRepository->update($request->all(), $id);

        Flash::success($tipo->tabla.' modificado correctamente.');

        return redirect(route('tipos.index'));
    }
}
