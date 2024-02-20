<?php

namespace App\Http\Controllers\API;

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
                        ->orderBy('nombre')->get();

        return $choferes;
    }

    public function show($id){
        return Chofer::find($id);
    }

    public function store(CreateChoferRequest $request)
    {
        $input = $request->all();
        $chofer = $this->choferRepository->create($input);
        return response()->json(['success' => true, 'message' => 'registrado correctamente']);
    }

    public function update($id, UpdateChoferRequest $request)
    {
        $chofer = $this->choferRepository->find($id);
        $chofer = $this->choferRepository->update($request->all(), $id);
        return response()->json(['success' => true, 'message' => 'modificado correctamente']);
    }

    public function destroy($id)
    {
        $chofer = $this->choferRepository->find($id);
        $this->choferRepository->delete($id);
        return response()->json(['success' => true, 'message' => 'eliminado correctamente']);
    }
}
