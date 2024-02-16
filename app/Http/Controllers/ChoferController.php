<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChoferRequest;
use App\Http\Requests\UpdateChoferRequest;
use App\Models\Chofer;
use App\Repositories\ChoferRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ChoferController extends AppBaseController
{
    /** @var  ChoferRepository */
    private $choferRepository;

    public function __construct(ChoferRepository $choferRepo)
    {
        $this->choferRepository = $choferRepo;
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
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $choferes = Chofer::where('nombre', 'ilike', '%' . $txtBuscar . '%')
                        ->orWhere('licencia', 'ilike', '%' . $txtBuscar . '%')
                        ->orderBy('nombre')
                        ->paginate();

        return view('choferes.index')
            ->with('choferes', $choferes);
    }

    /**
     * Show the form for creating a new Chofer.
     *
     * @return Response
     */
    public function create()
    {
        return view('choferes.create');
    }

    /**
     * Store a newly created Chofer in storage.
     *
     * @param CreateChoferRequest $request
     *
     * @return Response
     */
    public function store(CreateChoferRequest $request)
    {
        $input = $request->all();

        $chofer = $this->choferRepository->create($input);

        if($request->esFormulario)
        {
            return response()->json(['res' => true, 'message' => 'Chofer guardado correctamente.']);
        }
        else{

            Flash::success('Chofer guardado correctamente.');

            return redirect(route('choferes.index'));
        }
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
        $chofer = $this->choferRepository->find($id);

        if (empty($chofer)) {
            Flash::error('Chofer no encontrado');

            return redirect(route('choferes.index'));
        }

        return view('choferes.edit')->with('chofer', $chofer);
    }

    /**
     * Update the specified Chofer in storage.
     *
     * @param int $id
     * @param UpdateChoferRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChoferRequest $request)
    {
        $chofer = $this->choferRepository->find($id);

        if (empty($chofer)) {
            Flash::error('Chofer no encontrado');

            return redirect(route('choferes.index'));
        }

        $chofer = $this->choferRepository->update($request->all(), $id);

        Flash::success('Chofer modificado correctamente.');

        return redirect(route('choferes.index'));
    }

    public function getChoferes()
    {
        $choferes = Chofer::orderBy('nombre')->get()->pluck('info','id');
        return json_encode($choferes);
    }
    public function getChoferesAndroid()
    {
        $choferes = Chofer::orderBy('nombre')->get();
        return ($choferes);
    }
}
