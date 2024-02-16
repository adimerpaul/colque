<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Models\BasePlomoPlata;
use App\Models\Contrato;
use App\Models\DescuentoBonificacion;
use App\Models\TerminosPlomoPlata;
use App\Repositories\ContratoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ContratoController extends AppBaseController
{
    /** @var  ContratoRepository */
    private $contratoRepository;

    public function __construct(ContratoRepository $contratoRepo)
    {
        $this->contratoRepository = $contratoRepo;
    }

    /**
     * Display a listing of the Contrato.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $contratos = $this->contratoRepository->paginate(15);

        return view('contratos.index')
            ->with('contratos', $contratos);
    }

    /**
     * Show the form for creating a new Contrato.
     *
     * @return Response
     */
    public function create()
    {
        return view('contratos.create');
    }

    /**
     * Store a newly created Contrato in storage.
     *
     * @param CreateContratoRequest $request
     *
     * @return Response
     */
    public function store(CreateContratoRequest $request)
    {
        $input = $request->all();

        $contrato = $this->contratoRepository->create($input);


        Flash::success('Contrato guardado correctamente.');

        return redirect(route('contratos.show', $contrato->id));
    }

    /**
     * Display the specified Contrato.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contrato = $this->contratoRepository->find($id);

        if (empty($contrato)) {
            Flash::error('Contrato no encontrado');

            return redirect(route('contratos.index'));
        }

        return view('contratos.show')->with('contrato', $contrato);
    }

    /**
     * Show the form for editing the specified Contrato.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $contrato = $this->contratoRepository->find($id);

        if (empty($contrato)) {
            Flash::error('Contrato no encontrado');

            return redirect(route('contratos.index'));
        }
        return view('contratos.edit')->with('contrato', $contrato);
    }

    /**
     * Update the specified Contrato in storage.
     *
     * @param int $id
     * @param UpdateContratoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContratoRequest $request)
    {
        $contrato = $this->contratoRepository->find($id);

        if (empty($contrato)) {
            Flash::error('Contrato no encontrado');

            return redirect(route('contratos.index'));
        }

        $input = $request->all();
        $contratoCambio = $this->contratoRepository->update($input, $id);

        if($contratoCambio->bono_productor !=$contrato->bono_productor) {
            DescuentoBonificacion::whereNombre('BONO PRODUCTOR')->update(['valor' => $contratoCambio->bono_productor]);
            Contrato::where('id', '<>', $id)->update(['bono_productor' => $contratoCambio->bono_productor]);
        }

        if($contratoCambio->bono_equipamiento !=$contrato->bono_equipamiento) {
            DescuentoBonificacion::whereNombre('BONO EQUIPAMIENTO')->update(['valor' => $contratoCambio->bono_equipamiento]);
            Contrato::where('id', '<>', $id)->update(['bono_equipamiento' => $contratoCambio->bono_equipamiento]);
        }

        if($contratoCambio->bono_epp !=$contrato->bono_epp) {
            DescuentoBonificacion::whereNombre('BONO EPP')->update(['valor' => $contratoCambio->bono_epp]);
            Contrato::where('id', '<>', $id)->update(['bono_epp' => $contratoCambio->bono_epp]);
        }

        if($contratoCambio->bono_cliente !=$contrato->bono_cliente) {
            DescuentoBonificacion::whereNombre('BONO CLIENTE')->update(['valor' => $contratoCambio->bono_cliente]);
            Contrato::where('id', '<>', $id)->update(['bono_cliente' => $contratoCambio->bono_cliente]);
        }
        Flash::success('Contrato modificado correctamente.');

        return redirect(route('contratos.index'));
    }

    /**
     * Remove the specified Contrato from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contrato = $this->contratoRepository->find($id);

        if (empty($contrato)) {
            Flash::error('Contrato no encontrado');

            return redirect(route('contratos.index'));
        }

        $this->contratoRepository->delete($id);

        Flash::success('Contrato eliminado correctamente.');

        return redirect(route('contratos.index'));
    }

}
