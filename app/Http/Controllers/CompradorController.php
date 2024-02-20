<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompradorRequest;
use App\Http\Requests\UpdateCompradorRequest;
use App\Models\Comprador;
use App\Repositories\CompradorRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CompradorController extends AppBaseController
{
    /** @var  CompradorRepository */
    private $compradorRepository;

    public function __construct(CompradorRepository $compradorRepo)
    {
        $this->compradorRepository = $compradorRepo;
    }

    /**
     * Display a listing of the Comprador.
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

        $compradores = Comprador::
        whereEsAprobado(true)
            ->where(function ($q) use ($txtBuscar) {
                $q->where('razon_social', 'ilike', '%' . $txtBuscar . '%')
                    ->orWhere('nit', 'ilike', '%' . $txtBuscar . '%');
            })
            ->orderBy('razon_social')->paginate();

        return view('compradores.index')
            ->with('compradores', $compradores);
    }

    /**
     * Show the form for creating a new Comprador.
     *
     * @return Response
     */
    public function create()
    {
        return view('compradores.create');
    }

    /**
     * Store a newly created Comprador in storage.
     *
     * @param CreateCompradorRequest $request
     *
     * @return Response
     */
    public function store(CreateCompradorRequest $request)
    {
        $input = $request->all();

        $comprador = $this->compradorRepository->create($input);

        if ($request->esFormulario) {
            return response()->json(['res' => true, 'message' => 'Comprador guardado correctamente.']);
        } else {

            Flash::success('Comprador guardado correctamente.');

            return redirect(route('compradores.index'));
        }
    }

    /**
     * Show the form for editing the specified Comprador.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $comprador = $this->compradorRepository->find($id);

        if (empty($comprador)) {
            Flash::error('Comprador no encontrado');

            return redirect(route('compradores.index'));
        }

        return view('compradores.edit')->with('comprador', $comprador);
    }

    /**
     * Update the specified Comprador in storage.
     *
     * @param int $id
     * @param UpdateCompradorRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompradorRequest $request)
    {
        $comprador = $this->compradorRepository->find($id);

        if (empty($comprador)) {
            Flash::error('Comprador no encontrado');

            return redirect(route('compradores.index'));
        }

        $comprador = $this->compradorRepository->update($request->all(), $id);

        Flash::success('Comprador modificado correctamente.');

        return redirect(route('compradores.index'));
    }

    public function getCompradores()
    {
        $compradores = Comprador::whereEsAprobado(true)->orderBy('razon_social')->get()->pluck('info', 'id');
        return json_encode($compradores);
    }
}
